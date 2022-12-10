<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations\User;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Support\Str;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\User\ExternalModelRelationContract;

class ExternalModelRelation implements ExternalModelRelationContract
{
    protected string $idsProperty;
    protected string $modelsProperty;
    protected bool $multiple = true;
    protected ExternalModelRelationLoadingCallbackContract $callback;

    public function getIdsProperty(): string
    {
        return $this->idsProperty;
    }
    
    /** @return static */
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

    /** @return static */
    public function setModelsProperty(string $property): ExternalModelRelationContract
    {
        $this->modelsProperty = $property;

        return $this;
    }

    /** @return static */
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