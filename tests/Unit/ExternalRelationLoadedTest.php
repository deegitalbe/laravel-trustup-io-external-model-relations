<?php

namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Tests\Unit;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelRelatedModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Tests\TestCase;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Models\IsExternalModelRelatedModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExternalRelationLoadedTest extends TestCase
{
    private int $originalErrorReporting;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalErrorReporting = error_reporting();
    }

    protected function tearDown(): void
    {
        error_reporting($this->originalErrorReporting);

        parent::tearDown();
    }

    public function test_relation_reports_not_loaded_when_warnings_are_suppressed(): void
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

        $model = new ExternalRelationLoadedTestModel;

        $this->assertFalse($model->externalRelationLoaded('friend'));
    }

    public function test_relation_reports_loaded_once_set_when_warnings_are_suppressed(): void
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

        $model = new ExternalRelationLoadedTestModel;
        $model->loadExternalRelations('friend');

        $this->assertTrue($model->externalRelationLoaded('friend'));
    }

    public function test_relation_reports_not_loaded_when_warnings_are_enabled(): void
    {
        error_reporting(E_ALL);

        $model = new ExternalRelationLoadedTestModel;

        $this->assertFalse($model->externalRelationLoaded('friend'));
    }
}

class ExternalRelationLoadedTestModel extends Model implements ExternalModelRelatedModelContract
{
    use IsExternalModelRelatedModel;

    /** @return list<string> */
    public function getExternalRelationNames(): array
    {
        return ['friend'];
    }

    public function friend(): ExternalModelRelationContract
    {
        return $this->belongsToExternalModel(new ExternalRelationLoadedTestLoader, 'friend_id', 'friend');
    }
}

class ExternalRelationLoadedTestLoader implements ExternalModelRelationLoadingCallbackContract
{
    public function load(Collection $identifiers): Collection
    {
        return collect();
    }
}
