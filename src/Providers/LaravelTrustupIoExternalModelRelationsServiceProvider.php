<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Providers;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Package;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\TrustupUser;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\ExternalModelRelation;
use Henrotaym\LaravelPackageVersioning\Providers\Abstracts\VersionablePackageServiceProvider;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Collections\ExternalModelRelatedCollection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\ExternalModelRelationLoader;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\ExternalModelRelationSubscriber;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoaderContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationSubscriberContract;

class LaravelTrustupIoExternalModelRelationsServiceProvider extends VersionablePackageServiceProvider
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }

    protected function addToRegister(): void
    {
        $this->app->bind(ExternalModelRelationContract::class, ExternalModelRelation::class);
        $this->app->bind(ExternalModelRelationLoaderContract::class, ExternalModelRelationLoader::class);
        $this->app->bind(ExternalModelRelatedCollectionContract::class, ExternalModelRelatedCollection::class);
        $this->app->bind(ExternalModelRelationSubscriberContract::class, ExternalModelRelationSubscriber::class);
    }

    protected function addToBoot(): void
    {
        //
    }
}