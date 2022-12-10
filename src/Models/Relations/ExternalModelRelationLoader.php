<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Api\Endpoints\Auth\UserEndpointContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationLoaderContract;

class ExternalModelRelationLoader implements ExternalModelRelationLoaderContract
{
    /** @var Collection<int, ExternalModelRelationContract> */
    protected Collection $relations;

    /** @var Collection<int, Model> */
    protected Collection $models;

    /** @return static */
    public function addRelation(ExternalModelRelationContract $relation): ExternalModelRelationLoaderContract
    {
        $this->getRelations()->push($relation);

        return $this;
    }

    /**
     * Loading several external relations at once.
     * 
     * @param Collection<int, ExternalModelRelationContract> $relations
     * @return static
     */
    public function addRelations(Collection $relations): ExternalModelRelationLoaderContract
    {
        $this->getRelations()->push(...$relations);
        
        return $this;
    }

    /**
     * @param Collection<int, Model>
     * @return static
     */
    public function setModels(Collection $models): ExternalModelRelationLoaderContract
    {
        $this->models = $models;

        return $this;
    }

    /** @return static */
    public function load(): ExternalModelRelationLoaderContract
    {
        $this->models->each(fn (Model $model) =>
            $this->getRelations()->each(fn (ExternalModelRelationContract $relation) =>
                $this->setModelRelation($model, $relation)    
            )
        );

        return $this;
    }

    /** @return Collection<int ExternalModelRelationContract> */
    public function getRelations(): Collection
    {
        return $this->relations ??
            $this->relations = collect();
    }

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    protected function setModelRelation(Model $model, ExternalModelRelationContract $relation): void
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

    /** @return Collection<int, int|string> */
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
     * Getting users id map.
     * 
     * Key is user id and value is boolean.
     * 
     * @return Collection<int, bool>
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