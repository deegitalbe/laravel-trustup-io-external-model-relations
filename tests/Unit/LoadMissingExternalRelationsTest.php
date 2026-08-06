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

class LoadMissingExternalRelationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        SpyFriendLoader::reset();
    }

    public function test_model_load_missing_loads_a_never_loaded_relation(): void
    {
        $model = new LoadMissingTestModel(friendId: 'a');

        $model->loadMissingExternalRelations('friend');

        $this->assertSame(1, SpyFriendLoader::$calls);
        $this->assertTrue($model->externalRelationLoaded('friend'));
    }

    public function test_model_load_missing_does_not_reload_an_already_loaded_relation(): void
    {
        $model = new LoadMissingTestModel(friendId: 'a');
        $model->loadExternalRelations('friend');
        $this->assertSame(1, SpyFriendLoader::$calls);

        $model->loadMissingExternalRelations('friend');

        $this->assertSame(1, SpyFriendLoader::$calls);
    }

    public function test_model_eager_load_always_reloads(): void
    {
        $model = new LoadMissingTestModel(friendId: 'a');
        $model->loadExternalRelations('friend');
        $model->loadExternalRelations('friend');

        $this->assertSame(2, SpyFriendLoader::$calls);
    }

    public function test_collection_load_missing_loads_all_when_none_loaded(): void
    {
        $models = [new LoadMissingTestModel(friendId: 'a'), new LoadMissingTestModel(friendId: 'b')];
        $collection = $models[0]->newCollection($models);

        $collection->loadMissingExternalRelations('friend');

        $this->assertSame(1, SpyFriendLoader::$calls);
        $this->assertEqualsCanonicalizing(['a', 'b'], SpyFriendLoader::$lastIdentifiers);
    }

    public function test_collection_load_missing_only_loads_missing_members(): void
    {
        $loaded = new LoadMissingTestModel(friendId: 'a');
        $loaded->loadExternalRelations('friend');
        SpyFriendLoader::reset();

        $missing = new LoadMissingTestModel(friendId: 'b');

        $collection = $loaded->newCollection([$loaded, $missing]);
        $collection->loadMissingExternalRelations('friend');

        $this->assertSame(1, SpyFriendLoader::$calls);
        $this->assertEqualsCanonicalizing(['b'], SpyFriendLoader::$lastIdentifiers);
    }

    public function test_collection_load_missing_skips_when_all_loaded(): void
    {
        $a = new LoadMissingTestModel(friendId: 'a');
        $b = new LoadMissingTestModel(friendId: 'b');
        $collection = $a->newCollection([$a, $b]);
        $collection->loadExternalRelations('friend');
        SpyFriendLoader::reset();

        $collection->loadMissingExternalRelations('friend');

        $this->assertSame(0, SpyFriendLoader::$calls);
    }

    public function test_collection_load_missing_is_noop_on_empty_collection(): void
    {
        $collection = (new LoadMissingTestModel(friendId: 'a'))->newCollection([]);

        $collection->loadMissingExternalRelations('friend');

        $this->assertSame(0, SpyFriendLoader::$calls);
    }

    public function test_collection_load_missing_works_when_warnings_suppressed(): void
    {
        $original = error_reporting();
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

        try {
            $loaded = new LoadMissingTestModel(friendId: 'a');
            $loaded->loadExternalRelations('friend');
            SpyFriendLoader::reset();

            $missing = new LoadMissingTestModel(friendId: 'b');
            $collection = $loaded->newCollection([$loaded, $missing]);
            $collection->loadMissingExternalRelations('friend');

            $this->assertSame(1, SpyFriendLoader::$calls);
            $this->assertEqualsCanonicalizing(['b'], SpyFriendLoader::$lastIdentifiers);
        } finally {
            error_reporting($original);
        }
    }

    public function test_collection_load_missing_on_nested_leaf_models_only_loads_missing_leaves(): void
    {
        $preloadedLeaf = new LoadMissingTestModel(friendId: 'a');
        $preloadedLeaf->loadExternalRelations('friend');
        SpyFriendLoader::reset();

        $freshLeaf = new LoadMissingTestModel(friendId: 'b');

        $leafCollection = $preloadedLeaf->newCollection([$preloadedLeaf, $freshLeaf]);
        $leafCollection->loadMissingExternalRelations('friend');

        $this->assertSame(1, SpyFriendLoader::$calls);
        $this->assertEqualsCanonicalizing(['b'], SpyFriendLoader::$lastIdentifiers);
        $this->assertTrue($preloadedLeaf->externalRelationLoaded('friend'));
        $this->assertTrue($freshLeaf->externalRelationLoaded('friend'));
    }
}

class LoadMissingTestModel extends Model implements ExternalModelRelatedModelContract
{
    use IsExternalModelRelatedModel;

    public function __construct(?string $friendId = null)
    {
        parent::__construct();

        $this->friend_id = $friendId;
    }

    /** @return list<string> */
    public function getExternalRelationNames(): array
    {
        return ['friend'];
    }

    public function friend(): ExternalModelRelationContract
    {
        return $this->belongsToExternalModel(new SpyFriendLoader, 'friend_id', 'friend');
    }
}

class SpyFriendModel implements ExternalModelContract
{
    public function __construct(private readonly string $id) {}

    public function getExternalRelationIdentifier(): string|int
    {
        return $this->id;
    }
}

class SpyFriendLoader implements ExternalModelRelationLoadingCallbackContract
{
    public static int $calls = 0;

    /** @var list<string|int> */
    public static array $lastIdentifiers = [];

    public static function reset(): void
    {
        self::$calls = 0;
        self::$lastIdentifiers = [];
    }

    public function load(Collection $identifiers): Collection
    {
        self::$calls++;
        self::$lastIdentifiers = $identifiers->values()->all();

        return $identifiers->map(fn (string|int $id) => new SpyFriendModel((string) $id))->values();
    }
}
