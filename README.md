# Laravel trustup io auth client

## Installation

### Require package

```shell
composer require deegitalbe/laravel-trustup-io-external-model-relations
```

### Preparing your models
Your model having external relationships should look like this
```php
<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Models\IsExternalModelRelatedModel;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelRelatedModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;

class Post extends Model implements ExternalModelRelatedModelContract
{
    use IsExternalModelRelatedModel;

    /**
     * Defining contributors relation.
     * 
     * @return ExternalModelRelationContract
     */
    public function contributors(): ExternalModelRelationContract
    {
        return $this->hasManyExternalModels('contributor_ids');
    }

    /**
     * Defining contributors relation.
     * 
     * @return ExternalModelRelationContract
     */
    public function creator(): ExternalModelRelationContract
    {
        return $this->belongsToExternalModel('creator_id');
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
     * Getting related contributors.
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
