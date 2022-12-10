<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Tests;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Package;
use Henrotaym\LaravelPackageVersioning\Testing\VersionablePackageTestCase;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Providers\LaravelTrustupIoExternalModelRelationsServiceProvider;
use Henrotaym\LaravelApiClient\Providers\ClientServiceProvider;

class TestCase extends VersionablePackageTestCase
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }
    
    public function getServiceProviders(): array
    {
        return [
            ClientServiceProvider::class,
            LaravelTrustupIoExternalModelRelationsServiceProvider::class,
        ];
    }
}