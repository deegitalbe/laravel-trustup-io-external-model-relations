<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Collections;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Collections\IsExternalModelRelatedCollection;

/**
 * A custom model collection related to trustup users.
 */
class ExternalModelRelatedCollection extends EloquentCollection implements ExternalModelRelatedCollectionContract
{
    use IsExternalModelRelatedCollection;
}