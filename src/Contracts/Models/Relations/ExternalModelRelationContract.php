<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User;

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
     * Getting model property name where external models are stored.
     * 
     * @return string
     */
    public function getModelsProperty(): string;

    /**
     * Setting model property name where external models are stored.
     * 
     * @param string $property
     * @return static
     */
    public function setModelsProperty(string $property): ExternalModelRelationContract;

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
}