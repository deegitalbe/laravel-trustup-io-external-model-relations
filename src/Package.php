<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\PackageContract;
use Henrotaym\LaravelPackageVersioning\Services\Versioning\VersionablePackage;

class Package extends VersionablePackage implements PackageContract
{
    public static function prefix(): string
    {
        return "laravel-trustup-io-external-model-relations";
    }
}