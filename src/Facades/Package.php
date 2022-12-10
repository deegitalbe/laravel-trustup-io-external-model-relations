<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Facades;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Package as Underlying;
use Henrotaym\LaravelPackageVersioning\Facades\Abstracts\VersionablePackageFacade;

class Package extends VersionablePackageFacade
{
    public static function getPackageClass(): string
    {
        return Underlying::class;
    }
}