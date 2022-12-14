<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Support\Str;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExternalModelRelation implements ExternalModelRelationContract
{
    /**
     * Model property name where external model ids are stored.
     * 
     * @return string
     */
    protected string $idsProperty;

    /**
     * Tetting if related models should be retrieved as collection or single model.
     * 
     * @return string
     */
    protected bool $multiple = true;

    /**
     * Relation name.
     * 
     * @return string
     */
    protected string $name;

    /**
     * Related model.
     * 
     * @return Model
     */
    protected Model $model;

    /**
     * Callback able to load external models.
     * 
     * @return string
     */
    protected ExternalModelRelationLoadingCallbackContract $callback;

    public function getIdsProperty(): string
    {
        return $this->idsProperty;
    }
    
    public function setIdsProperty(string $property): ExternalModelRelationContract
    {
        $this->idsProperty = $property;

        return $this;
    }

    public function setName(string $name): ExternalModelRelationContract
    {
        $this->name = $name;

        return $this;
    }
    
    public function getName(): string
    {
        return $this->name ??
            $this->name = $this->formatName();
    }

    protected function formatName(): string
    {
        if ($this->isMultiple()):
            return Str::plural(str_replace("_ids", "", $this->idsProperty));
        endif;

        return str_replace("_id", "", $this->idsProperty);
    }

    public function setMultiple(bool $isMultiple = true): ExternalModelRelationContract
    {
        $this->multiple = $isMultiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function getLoadingCallback(): ExternalModelRelationLoadingCallbackContract
    {
        return $this->callback;
    }

    public function setLoadingCallback(ExternalModelRelationLoadingCallbackContract $callback): ExternalModelRelationContract
    {
        $this->callback = $callback;

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): ExternalModelRelationContract
    {
        $this->model = $model;

        return $this;
    }

    public function setRelatedModelsByIds(Collection|int|string|null $ids): ExternalModelRelationContract
    {
        $this->model->{$this->getIdsProperty()} = $ids instanceof Collection && !$this->isMultiple() ?
            $ids->first()
            : $ids;
        $this->model->save();

        return $this;
    }

    public function setRelatedModels(Collection|ExternalModelContract|null $models): ExternalModelRelationContract
    {
        return $this->setRelatedModelsByIds(
            $this->formatRelatedModels($models)
                ->map(fn (ExternalModelContract $model) => $model->getExternalRelationIdentifier())
        );
    }

    public function addToRelatedModels(Collection|ExternalModelContract $models): ExternalModelRelationContract
    {
        return $this->addToRelatedModelsByIds(
            $this->formatRelatedModels($models)
                ->map(fn (ExternalModelContract $model) => $model->getExternalRelationIdentifier())
        );
    }

    public function addToRelatedModelsByIds(Collection|int|string $ids): ExternalModelRelationContract
    {
        if (!$this->isMultiple()):
            $this->model->{$this->getIdsProperty()} = $ids instanceof Collection ? $ids->first() : $ids;
        else:
            $this->model->{$this->getIdsProperty()} = ($this->model->{$this->getIdsProperty()} ?: collect())
                ->push(...($ids instanceof Collection ? $ids : collect($ids)));
        endif;

        $this->model->save();

        return $this;
    }

    public function isUsingSameCallback(ExternalModelRelationContract $relation): bool
    {
        return get_class($this->getLoadingCallback()) === get_class($relation->getLoadingCallback());
    }

    /**
     * Formating given models.
     * 
     * @param Collection<int, ExternalModelContract>|ExternalModelContract|null $models
     * @return Collection<int, ExternalModelContract>
     */
    protected function formatRelatedModels(Collection|ExternalModelContract|null $models): Collection
    {
        if (!$models) return collect();

        if ($models instanceof Collection) return $models;

        return collect([$models]);
    }
}