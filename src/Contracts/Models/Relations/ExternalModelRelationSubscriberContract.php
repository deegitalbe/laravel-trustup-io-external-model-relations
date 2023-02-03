<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;

interface ExternalModelRelationSubscriberContract
{
    /**
     * Setting related model.
     * 
     * @param Model $model
     * @return static
     */
    public function setModel(Model $model): ExternalModelRelationSubscriberContract;

    /**
     * Getting related model
     * 
     * @return Model
     */
    public function getModel(): Model;
    
    /**
     * Registering given relation to model (if not registered yet).
     * 
     * @param ExternalModelRelationContract $relation
     * @return static
     */
    public function register(ExternalModelRelationContract $relation): ExternalModelRelationSubscriberContract;

    /**
     * Telling if given relation is already registered.
     * 
     * @param ExternalModelRelationContract $relation
     * @return static
     */
    public function isRegistered(ExternalModelRelationContract $relation): bool;
}