<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Providers;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Package;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\TrustupUser;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Collections\ExternalModelRelatedCollection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User\ExternalModelRelation;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User\ExternalModelRelationLoader;
use Henrotaym\LaravelPackageVersioning\Providers\Abstracts\VersionablePackageServiceProvider;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationLoaderContract;

class LaravelTrustupIoExternalModelRelationsServiceProvider extends VersionablePackageServiceProvider
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }

    protected function addToRegister(): void
    {
        $this->app->bind(ExternalModelContract::class, TrustupUser::class);
        $this->app->bind(ExternalModelRelationContract::class, ExternalModelRelation::class);
        $this->app->bind(ExternalModelRelationLoaderContract::class, ExternalModelRelationLoader::class);
        $this->app->bind(ExternalModelRelatedCollectionContract::class, ExternalModelRelatedCollection::class);
    }

    protected function addToBoot(): void
    {
        //
    }
}