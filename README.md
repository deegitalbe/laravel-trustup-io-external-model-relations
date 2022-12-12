# laravel-trustup-io-external-model-relations

## Installation

### Require package

```shell
composer require deegitalbe/laravel-trustup-io-external-model-relations
```

### Define a loader
Each external relation should have its own loader that is implementing `ExternalModelRelationLoadingCallbackContract`.

```php
<?php
namespace App\Models\Relations;

use Deegitalbe\LaravelTrustupIoAuthClient\Contracts\Api\Endpoints\Auth\UserEndpointContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Support\Collection;

class TrustupUserRelationLoadingCallback implements ExternalModelRelationLoadingCallbackContract
{
    /**
     * User endpoint.
     * 
     * @var UserEndpointContract
     */
    protected UserEndpointContract $endpoint;

    /**
     * Constructing instance.
     * 
     * @param UserEndpointContract $endpoint
     * @return void
     */
    public function __construct(UserEndpointContract $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function load(Collection $identifiers): Collection
    {
        return $this->endpoint->byIds($identifiers);
    }
}
```

### Preparing your models
Your model having external relationships should look like this
```php
<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use App\Models\Relations\TrustupUserRelationLoadingCallback;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Models\IsExternalModelRelatedModel;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelRelatedModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;

class Post extends Model implements ExternalModelRelatedModelContract
{
    use IsExternalModelRelatedModel;

    /**
     * Getting external relation names.
     * 
     * @return array<int, string>
     */
    public function getExternalRelationNames(): array
    {
        return [
            'contributors',
            'creator'
        ];
    }

    /**
     * Defining contributors relation.
     * 
     * @return ExternalModelRelationContract
     */
    public function contributors(): ExternalModelRelationContract
    {
        return $this->hasManyExternalModels(app()->make(TrustupUserRelationLoadingCallback::class), 'contributor_ids');
    }

    /**
     * Defining creator relation.
     * 
     * @return ExternalModelRelationContract
     */
    public function creator(): ExternalModelRelationContract
    {
        return $this->belongsToExternalModel(app()->make(TrustupUserRelationLoadingCallback::class), 'creator_id');
    }

    /**
     * Getting related contributors.
     * 
     * @return Collection<int, ExternalModelContract>
     */
    public function getContributors(): Collection
    {
        return $this->getExternalModels('contributors');
    }

    /**
     * Getting related creator.
     * 
     * @return ?ExternalModelContract
     */
    public function getCreator(): ?ExternalModelContract
    {
        return $this->getExternalModels('creator');
    }

    /**
     * Setting related creator (OPTIONAL).
     * 
     * @param ?int $creatorId
     * @return static
     */
    public function setCreator(?int $creatorId): self
    {
        $this->creator()->setRelatedModelsByIds($creatorId);

        return $this;
    }

    /**
     * Setting related contributors (OPTIONAL).
     * 
     * @param Collection<int, int> $contributorIds
     * @return static
     */ 
    public function setContributors(Collection $contributorIds): self
    {
        $this->contributors()->setRelatedModelsByIds($contributorIds);

        return $this;
    }
}
```

### Exposing your models by creating a resource
If you wanna expose your model, here is an example resource based on previous section model
```php
<?php

namespace App\Http\Resources;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Resources\TrustupUserResource;
use App\Resources\TrustupUserResource;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Resources\ExternalModelRelatedResource;

class PostResource extends ExternalModelRelatedResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'contributors' => TrustupUserResource::collection($this->whenExternalModelsLoaded('contributors')),
            'creator' => new TrustupUserResource($this->whenExternalModelsLoaded('creator'))
        ];
    }
}
```

### Eager load collections

Only one request will be performed even if you load multiple relations ⚡⚡⚡⚡

```php
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::all()->loadExternalRelations('contributors', 'creator'));
    }
}
```
