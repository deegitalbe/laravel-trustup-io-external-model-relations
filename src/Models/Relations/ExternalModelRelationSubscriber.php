<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationSubscriberContract;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;

class ExternalModelRelationSubscriber implements ExternalModelRelationSubscriberContract
{
    protected Model $model;
    /** 
     * @var Collection<string, ExternalModelRelationContract>
     */
    protected Collection $mapping;

    public function setModel(Model $model): ExternalModelRelationSubscriberContract
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
 
    public function register(ExternalModelRelationContract $relation): ExternalModelRelationSubscriberContract
    {
        if ($this->isRegistered($relation)) return $this;

        $this->model->mergeFillable([$relation->getIdsProperty()]);
        if (!$relation->isMultiple()) return $this;

        $this->model->mergeCasts([$relation->getIdsProperty() => AsCollection::class]);
        return $this;
    }

    public function isRegistered(ExternalModelRelationContract $relation): bool
    {
        return !!$this->getMapping()->get($relation->getName());
    }

    protected function getMapping(): Collection
    {
        return $this->mapping ??
            $this->mapping = collect();
    }
}