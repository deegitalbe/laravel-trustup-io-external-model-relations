<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Models\Relations;

use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationLoadingCallbackContract;
use Illuminate\Support\Str;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations\ExternalModelRelationContract;

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
}