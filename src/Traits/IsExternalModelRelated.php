<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits;

use Illuminate\Support\Collection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoaderContract;

trait IsExternalModelRelated
{
    /**
     * Getting a new external relation loader.
     * 
     * @return ExternalModelRelationLoaderContract
     */
    protected function newExternalModelRelationLoader(): ExternalModelRelationLoaderContract
    {
        return app()->make(ExternalModelRelationLoaderContract::class);
    }

    /**
     * Loading single relation.
     * 
     * @param ExternalModelRelationContract $relation Relation to load
     * @return static
     */
    protected function loadExternalModelRelation(ExternalModelRelationContract $relation)
    {
        $this->newExternalModelRelationLoader()
            ->addRelation($relation)
            ->setModels($this->getExternalModelRelatedModels())
            ->load();

        return $this;
    }

    /**
     * Loading several external relations at once.
     * 
     * @param Collection<int, ExternalModelRelationContract> $relations Relations to load
     * @return static
     */
    protected function loadExternalModelRelations(Collection $relations)
    {
        $this->newExternalModelRelationLoader()
            ->addRelations($relations)
            ->setModels($this->getExternalModelRelatedModels())
            ->load();

        return $this;
    }
}