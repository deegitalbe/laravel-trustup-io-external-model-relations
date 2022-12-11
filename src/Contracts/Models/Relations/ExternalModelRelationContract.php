<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations;

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
}