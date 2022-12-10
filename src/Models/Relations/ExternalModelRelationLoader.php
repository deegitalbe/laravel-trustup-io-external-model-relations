<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationLoaderContract;

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
        $this->models->each(fn (Model $model) =>
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
     * @param Model $model
     * @param ExternalModelRelationContract $relation
     * @return void
     */
    protected function setModelRelationExternalModels(Model $model, ExternalModelRelationContract $relation): void
    {
        $externalModels = $this->getModelRelationIdentifiers($model, $relation)
            ->reduce(fn (Collection $externalModels, int|string $externalModelIdentifier) =>
                ($model = $this->getExternalModelsMap($relation)[$externalModelIdentifier] ?? null) ?
                    $externalModels->push($model)
                    : $externalModels,
                collect()
            );
        
        $model->{$relation->getModelsProperty()} = $relation->isMultiple() ?
            $externalModels
            : $externalModels->first();
    }

    /**
     * Getting external model identifiers for given model and relation.
     * 
     * @param Model $model
     * @param ExternalModelRelationContract $relation
     * @return Collection<int, int|string>
     */
    protected function getModelRelationIdentifiers(Model $model, ExternalModelRelationContract $relation): Collection
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

        return $this->{$relation->getIdsProperty()} = $this->models->reduce(fn (Collection $map, Model $model) =>
            tap($map, fn () =>
                $this->getRelations()->each(fn (ExternalModelRelationContract $relation) => 
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
        if (isset($this->{$relation->getModelsProperty()})) return $this->{$relation->getModelsProperty()};

        $models = $relation->getLoadingCallback()->load($this->getExternalModelIdsMap($relation)->keys());

        return $this->{$relation->getModelsProperty()} = $models->reduce(fn (Collection $map, ExternalModelContract $model) =>
            tap($map, fn () =>
                $map[$model->getExternalRelationIdentifier()] = $model
            ),
            collect()
        );
    }
}