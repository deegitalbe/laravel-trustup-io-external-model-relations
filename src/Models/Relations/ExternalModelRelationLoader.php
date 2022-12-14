<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelRelatedModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoaderContract;

class ExternalModelRelationLoader implements ExternalModelRelationLoaderContract
{
    /**
     * Configured relations.
     * 
     * @var Collection<int, ExternalModelRelationContract>
     */
    protected Collection $relations;

    /**
     * Related models where configured relations should be loaded.
     * 
     * @var Collection<int, Model>
     */
    protected Collection $models;

    public function addRelation(ExternalModelRelationContract $relation): ExternalModelRelationLoaderContract
    {
        $this->getRelations()->push($relation);

        return $this;
    }

    public function addRelations(Collection $relations): ExternalModelRelationLoaderContract
    {
        $this->getRelations()->push(...$relations);
        
        return $this;
    }

    public function setModels(Collection $models): ExternalModelRelationLoaderContract
    {
        $this->models = $models;

        return $this;
    }

    public function load(): ExternalModelRelationLoaderContract
    {
        $this->models->each(fn (ExternalModelRelatedModelContract $model) =>
            $this->getRelations()->each(fn (ExternalModelRelationContract $relation) =>
                $this->setModelRelationExternalModels($model, $relation)    
            )
        );

        return $this;
    }

    public function getRelations(): Collection
    {
        return $this->relations ??
            $this->relations = collect();
    }

    public function getModels(): Collection
    {
        return $this->models;
    }

    /**
     * Setting given model external models for given relation
     * 
     * @param ExternalModelRelatedModelContract $model
     * @param ExternalModelRelationContract $relation
     * @return void
     */
    protected function setModelRelationExternalModels(ExternalModelRelatedModelContract $model, ExternalModelRelationContract $relation): void
    {
        $externalModels = $this->getModelRelationIdentifiers($model, $relation)
            ->reduce(fn (Collection $externalModels, int|string $externalModelIdentifier) =>
                ($model = $this->getExternalModelsMap($relation)[$externalModelIdentifier] ?? null) ?
                    $externalModels->push($model)
                    : $externalModels,
                collect()
            );
        
        $model->setExternalModels(
            $relation,
            $relation->isMultiple() ?
                $externalModels
                : $externalModels->first()
        );
    }

    /**
     * Getting external model identifiers for given model and relation.
     * 
     * @param ExternalModelRelatedModelContract $model
     * @param ExternalModelRelationContract $relation
     * @return Collection<int, int|string>
     */
    protected function getModelRelationIdentifiers(ExternalModelRelatedModelContract $model, ExternalModelRelationContract $relation): Collection
    {
        $ids = $model->{$relation->getIdsProperty()};

        if (!$ids) return collect();

        if (!$relation->isMultiple()):
            return collect([$ids]);
        endif;

        return $ids instanceof Collection ?
            $ids
            : collect($ids);
    }

    /**
     * Getting external models identifiers map matching given relation.
     * 
     * Key is identifier and value is boolean.
     * 
     * @param ExternalModelRelationContract $relation
     * @return Collection<string|int, bool>
     */
    protected function getExternalModelIdsMap(ExternalModelRelationContract $relation): Collection
    {
        if (isset($this->{$relation->getIdsProperty()})) return $this->{$relation->getIdsProperty()};

        return $this->{$relation->getIdsProperty()} = $this->models->reduce(fn (Collection $map, ExternalModelRelatedModelContract $model) =>
            tap($map, fn () =>
                $this->getRelations()
                    // Getting all relations having same callback at once.
                    ->filter(fn (ExternalModelRelationContract $relatedRelation) => 
                        $relation->isUsingSameCallback($relatedRelation)
                    )
                    // Grouping all relation model ids together.
                    ->each(fn (ExternalModelRelationContract $relation) => 
                        $this->getModelRelationIdentifiers($model, $relation)
                            ->each(fn (int|string $identifier) => $map[$identifier] = true)
                    )
            ),
            collect()
        );
    }


    /**
     * Getting external models map matching given relation.
     * 
     * Key is external model identifier and value is actual external model.
     * 
     * @param ExternalModelRelationContract $relation
     * @return Collection<string|int, ExternalModelContract>
     */
    protected function getExternalModelsMap(ExternalModelRelationContract $relation): Collection
    {
        // Getting all relations having same callback at once.
        $relatedRelations = $this->getRelations()
            ->filter(fn (ExternalModelRelationContract $relatedRelation) => 
                $relation->isUsingSameCallback($relatedRelation)
            );

        // If any relation using same callback is already loaded, use it.
        foreach ($relatedRelations as $relatedRelation):
            if (isset($this->{$relatedRelation->getName()})) return $this->{$relatedRelation->getName()};
        endforeach;

        $models = $relation->getLoadingCallback()->load($this->getExternalModelIdsMap($relation)->keys());

        return $this->{$relation->getName()} = $models->reduce(fn (Collection $map, ExternalModelContract $model) =>
            tap($map, fn () =>
                $map[$model->getExternalRelationIdentifier()] = $model
            ),
            collect()
        );
    }
}