<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\IsExternalModelRelated;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelRelatedModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Database\Eloquent\Casts\AsCollection;

/**
 * Handling external relations.
 */
trait IsExternalModelRelatedModel
{
    use IsExternalModelRelated;

    public function initializeIsExternalModelRelatedModel()
    {
        $this->getExternalRelationsCollection($this->getExternalRelationNames())
            ->each(function (ExternalModelRelationContract $relation) {
                $this->fillable[] = $relation->getIdsProperty();
                if (!$relation->isMultiple()) return;

                $this->casts[$relation->getIdsProperty()] = AsCollection::class;
            });
    }

    /**
     * Getting external relation names.
     * 
     * @return array<int, string>
     */
    public function getExternalRelationNames(): array
    {
        return [];
    }

    /**
     * Loaded external relations.
     * 
     * @var array<int, string>
     */ 
    protected array $externalRelations = [];

    /**
     * Getting external models relation based on given relation name.
     * 
     * You can expect ExternalModelContract|null for non-multiple relation or Collection<int, ExternalModelContract> for multiple relation.
     * 
     * @param string $relation Relation name to get
     * @return ?ExternalModelContract|Collection<int, ExternalModelContract>
     */
    public function getExternalModels(string $relationName): ExternalModelContract|Collection|null
    {
        return $this->getExternalModelRelationModels($this->{$relationName}());
    }

    /**
     * Setting given relation external models.
     * 
     * @param string $relationName
     * @param ?ExternalModelContract|Collection<int, ExternalModelContract> $models
     */
    public function setExternalModels(ExternalModelRelationContract $relation, ExternalModelContract|Collection|null $models): ExternalModelRelatedModelContract
    {
        $this->externalRelations[$relation->getName()] = $models;

        return $this;
    }

    /**
     * Loading external relations based on given relation names.
     * 
     * @param string $relationNames relation names to load.
     * @return static
     */
    public function loadExternalRelations(...$relationNames): ExternalModelRelatedModelContract
    {
        return $this->loadExternalModelRelations($this->getExternalRelationsCollection($relationNames));
    }

    /**
     * Creating a new belongs to external models relation.
     * 
     * @param ExternalModelRelationLoadingCallbackContract $callback Callback able to load related models
     * @param string $idProperty Model property containing related id.
     * @param ?string $name Name where related model should be stored.
     * @return ExternalModelRelationContract
     */
    public function belongsToExternalModel(ExternalModelRelationLoadingCallbackContract $callback, string $idProperty, ?string $name = null): ExternalModelRelationContract
    {
        $relation = $this->newExternalModelRelation()
            ->setIdsProperty($idProperty)
            ->setMultiple(false)
            ->setLoadingCallback($callback);

        return $name ?
            $relation->setName($name)
            : $relation;
    }

    /**
     * Creating a new has many external models relation.
     * 
     * @param ExternalModelRelationLoadingCallbackContract $callback Callback able to load related models
     * @param string $idsProperty Model property containing external model ids.
     * @param ?string $name Name where related models should be stored.
     * @return ExternalModelRelationContract
     */
    public function hasManyExternalModels(ExternalModelRelationLoadingCallbackContract $callback, string $idsProperty, ?string $name = null): ExternalModelRelationContract
    {
        $relation = $this->newExternalModelRelation()->setIdsProperty($idsProperty)
            ->setMultiple(true)
            ->setLoadingCallback($callback);

        return $name ?
            $relation->setName($name)
            : $relation;
    }

    /**
     * Telling if given external relation is loaded.
     * 
     * @param string $relationName Relation name to check.
     * @return bool
     */
    public function externalRelationLoaded(string $relationName): bool
    {
        /** @var ExternalModelRelationContract */
        $relation = $this->{$relationName}();

        [$error] = Helpers::try(fn () => $this->externalRelations[$relation->getName()]);

        return !$error;
    }

    /**
     * Getting external relations from given names.
     * 
     * @param array $relationNames Relation names to get
     * @return Collection<int, ExternalModelRelationContract>
     */
    public function getExternalRelationsCollection(array $relationNames): Collection
    {
        return collect($relationNames)->map(fn (string $relation) => $this->{$relation}());
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return ExternalModelRelatedCollectionContract
     */
    public function newCollection(array $models = [])
    {
        return app()->make(ExternalModelRelatedCollectionContract::class, ['items' => $models]);
    }

    /**
     * Creating an empty external model relation.
     * 
     * Do not forget to use setters to register your relation correctly.
     * 
     * @return ExternalModelRelationContract
     */
    protected function newExternalModelRelation(): ExternalModelRelationContract
    {
        return app()->make(ExternalModelRelationContract::class)
            ->setModel($this);
    }

    /**
     * Getting models related to external models.
     * 
     * @return Collection<int, Model>
     */
    protected function getExternalModelRelatedModels(): Collection
    {
        return collect([$this]);
    }

    /**
     * Getting external models matching given relation.
     * 
     * You can expect ExternalModelContract|null for non-multiple relation or Collection<int, ExternalModelContract> for multiple relation.
     * 
     * @param ExternalModelRelationContract $relation Relation to load
     * @return ?ExternalModelContract|Collection<int, ExternalModelContract>
     */
    protected function getExternalModelRelationModels(ExternalModelRelationContract $relation): mixed
    {
        [$error, $value] = Helpers::try(fn () => $this->externalRelations[$relation->getName()]);

        if (!$error):
            return $value;
        endif;

        $this->loadExternalModelRelation($relation);

        return $this->externalRelations[$relation->getName()];
    }
}