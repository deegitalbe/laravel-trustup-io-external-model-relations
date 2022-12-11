<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;

/**
 * Representing a loader able to lood several external relations at once.
 */
interface ExternalModelRelationLoaderContract
{
    /**
     * Adding a relation.
     * 
     * @param ExternalModelRelationContract $relation
     * @return static
     */
    public function addRelation(ExternalModelRelationContract $relation): ExternalModelRelationLoaderContract;

    /**
     * Loading several user relations at once.
     * 
     * @param Collection<int, ExternalModelRelationContract> $relations
     * @return static
     */
    public function addRelations(Collection $relations): ExternalModelRelationLoaderContract;

    /**
     * Getting configured relations.
     * 
     * @return Collection<int, ExternalModelRelationContract>
     */
    public function getRelations(): Collection;

    /**
     * Setting related models.
     * 
     * @param Collection<int, Model> $models
     * @return static
     */
    public function setModels(Collection $models): ExternalModelRelationLoaderContract;

    /**
     * Getting related models.
     * 
     * @return Collection<int, Model>
     */
    public function getModels(): Collection;

    /**
     * Loading configured relations.
     * 
     * @return static
     */
    public function load(): ExternalModelRelationLoaderContract;
}