<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Resources;

use Illuminate\Support\Collection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Illuminate\Http\Resources\MissingValue;

/**
 * Handling external relations.
 */
trait IsExternalModelRelatedResource
{
    /**
     * Retrieve a relationship if it has been loaded.
     * 
     * @param string $relationName Relation name to potentially retrieve
     * @return MissingValue|Collection<int, ExternalModelContract>|?ExternalModelContract
     */
    public function whenExternalRelationLoaded(string $relationName): mixed
    {
        return $this->resource->externalRelationLoaded($relationName)
            ? $this->resource->getExternalModels($relationName)
            : new MissingValue;
    }
}