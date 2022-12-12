<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Collections;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\IsExternalModelRelated;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections\ExternalModelRelatedCollectionContract;

/**
 * Handling external relations.
 */
trait IsExternalModelRelatedCollection
{
    use IsExternalModelRelated;

    /**
     * Load external relations.
     * 
     * @param string $relationNames relation names to load.
     * @return static
     */
    public function loadExternalRelations(...$relationNames): ExternalModelRelatedCollectionContract
    {
        if ($this->isEmpty()) return $this;

        return $this->loadExternalModelRelations($this->first()->getExternalRelationsCollection($relationNames));
    }

    /**
     * Getting models related to external models.
     * 
     * @return Collection<int, Model>
     */
    protected function getExternalModelRelatedModels(): Collection
    {
        return $this;
    }
}