<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models;

use Illuminate\Support\Collection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationSubscriberContract;

/**
 * Representing a model related to external models.
 */
interface ExternalModelRelatedModelContract
{
    /**
     * Getting external relation names.
     * 
     * @return array<int, string>
     */
    public function getExternalRelationNames(): array;

    /**
     * Getting external models relation based on given relation name.
     * 
     * You can expect ExternalModelContract|null for non-multiple relation or Collection<int, ExternalModelContract> for multiple relation.
     * 
     * @param string $relation Relation name to get
     * @return ?ExternalModelContract|Collection<int, ExternalModelContract>
     */
    public function getExternalModels(string $relationName): ExternalModelContract|Collection|null;

    /**
     * Setting given relation external models.
     * 
     * @param string $relationName
     * @param ?ExternalModelContract|Collection<int, ExternalModelContract> $models
     */
    public function setExternalModels(ExternalModelRelationContract $relation, ExternalModelContract|Collection|null $models): ExternalModelRelatedModelContract;

    /**
     * Loading external relations based on given relation names.
     * 
     * @param string $relationNames relation names to load.
     * @return static
     */
    public function loadExternalRelations(...$relationNames): ExternalModelRelatedModelContract;

    /**
     * Creating a new belongs to external models relation.
     * 
     * @param ExternalModelRelationLoadingCallbackContract $callback Callback able to load related models
     * @param string $idProperty Model property containing related id.
     * @param ?string $name Name where related model should be stored.
     * @return ExternalModelRelationContract
     */
    public function belongsToExternalModel(ExternalModelRelationLoadingCallbackContract $callback, string $idProperty, ?string $name = null): ExternalModelRelationContract;

     /**
     * Creating a new has many external models relation.
     * 
     * @param ExternalModelRelationLoadingCallbackContract $callback Callback able to load related models
     * @param string $idsProperty Model property containing external model ids.
     * @param ?string $name Name where related models should be stored.
     * @return ExternalModelRelationContract
     */
    public function hasManyExternalModels(ExternalModelRelationLoadingCallbackContract $callback, string $idsProperty, ?string $name = null): ExternalModelRelationContract;

    /**
     * Telling if given external relation is loaded.
     * 
     * @param string $relationName Relation name to check.
     * @return bool
     */
    public function externalRelationLoaded(string $relationName): bool;

    /**
     * Getting external relations from given names.
     * 
     * @param array $relationNames Relation names to get
     * @return Collection<int, ExternalModelRelationContract>
     */
    public function getExternalRelationsCollection(array $relationNames): Collection;

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return ExternalModelRelatedCollectionContract
     */
    public function newCollection(array $models = []);

    /**
     * Getting external relations subscriber.
     * 
     * @return ExternalModelRelationSubscriberContract
     */
    public function getExternalModelRelationSubscriber(): ExternalModelRelationSubscriberContract;
}