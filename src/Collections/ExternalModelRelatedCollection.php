<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Collections;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Collections\IsExternalModelRelatedCollection;

/**
 * Collection able to handle external relations.
 */
class ExternalModelRelatedCollection extends EloquentCollection implements ExternalModelRelatedCollectionContract
{
    use IsExternalModelRelatedCollection;
}