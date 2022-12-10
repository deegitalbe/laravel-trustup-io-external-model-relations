<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Tests\Unit;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Tests\TestCase;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Henrotaym\LaravelPackageVersioning\Testing\Traits\InstallPackageTest;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Api\Endpoints\Auth\UserEndpointContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationLoaderContract;

class InstallingPackageTest extends TestCase
{
    use InstallPackageTest;

    public function test_it_can_instanciate()
    {
        $this->app->make(UserEndpointContract::class);
        $this->app->make(ExternalModelContract::class);
        $this->app->make(ExternalModelRelationContract::class);
        $this->app->make(ExternalModelRelationLoaderContract::class);

        $this->assertTrue(true);
    }
}