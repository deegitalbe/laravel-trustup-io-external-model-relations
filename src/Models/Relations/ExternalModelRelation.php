<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Support\Str;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;

class ExternalModelRelation implements ExternalModelRelationContract
{
    /**
     * Model property name where external model ids are stored.
     * 
     * @return string
     */
    protected string $idsProperty;

    /**
     * Model property name where external models are stored.
     * 
     * @return string
     */
    protected string $modelsProperty;

    /**
     * Tetting if related models should be retrieved as collection or single model.
     * 
     * @return string
     */
    protected bool $multiple = true;

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
    
    public function getModelsProperty(): string
    {
        return $this->modelsProperty ??
            $this->modelsProperty = $this->formatModelsProperty();
    }

    protected function formatModelsProperty(): string
    {
        if ($this->isMultiple()):
            return Str::plural(str_replace("_ids", "", $this->idsProperty));
        endif;

        return str_replace("_id", "", $this->idsProperty);
    }

    public function setModelsProperty(string $property): ExternalModelRelationContract
    {
        $this->modelsProperty = $property;

        return $this;
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
}