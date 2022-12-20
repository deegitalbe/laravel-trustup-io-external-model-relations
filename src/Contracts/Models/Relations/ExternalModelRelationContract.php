<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;

/**
 * Representing an external model relation.
 */
interface ExternalModelRelationContract
{
    /**
     * Getting model property name where external ids are stored.
     * 
     * @return string
     */
    public function getIdsProperty(): string;
    
    /**
     * Setting model property name where external ids are stored.
     * 
     * @param string $property
     * @return static
     */
    public function setIdsProperty(string $property): ExternalModelRelationContract;

    /**
     * Setting if related models should be retrieved as collection or single model.
     * 
     * @param bool $isMultiple if true => collection, else => single user
     * @return static
     */
    public function setMultiple(bool $isMultiple = true): ExternalModelRelationContract;

    /**
     * Getting if related models should be retrieved as collection or single model.
     * 
     * @return bool
     */
    public function isMultiple(): bool;

    /**
     * Getting relation name.
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Setting relation name.
     * 
     * @param string $name
     * @return ExternalModelRelationContract
     */
    public function setName(string $name): ExternalModelRelationContract;

    /**
     * Getting callback able to load external models.
     * 
     * @return ExternalModelRelationLoadingCallbackContract
     */
    public function getLoadingCallback(): ExternalModelRelationLoadingCallbackContract;

    /**
     * Setting callback able to load external models.
     * 
     * @param ExternalModelRelationLoadingCallbackContract $callback
     * @return static
     */
    public function setLoadingCallback(ExternalModelRelationLoadingCallbackContract $callback): ExternalModelRelationContract;

    /**
     * Getting related model.
     * 
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Setting related model.
     * 
     * @param Model $model
     * @return static
     */
    public function setModel(Model $model): ExternalModelRelationContract;

    /**
     * Saving related models.
     * 
     * @param Collection<int, ExternalModelContract>|?ExternalModelContract $models
     * @return static
     */
    public function setRelatedModels(Collection|ExternalModelContract|null $models): ExternalModelRelationContract;

    /**
     * Saving related model ids.
     * 
     * @param Collection<int, int|string>|int|string|null $ids
     * @return static
     */
    public function setRelatedModelsByIds(Collection|int|string|null $ids): ExternalModelRelationContract;

    /**
     * Adding given models to related models.
     * 
     * @param Collection<int, ExternalModelContract>|ExternalModelContract $models
     * @return static
     */
    public function addToRelatedModels(Collection|ExternalModelContract $models): ExternalModelRelationContract;

    /**
     * Adding given identifiers to related model ids.
     * 
     * @param Collection<int, int|string>|int|string $ids
     * @return static
     */
    public function addToRelatedModelsByIds(Collection|int|string $ids): ExternalModelRelationContract;

    /**
     * Telling if relation is using same callback than given relation.
     * 
     * @param ExternalModelRelationContract $relation
     * @return bool
     */
    public function isUsingSameCallback(ExternalModelRelationContract $relation): bool;
}