<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Collections;

/**
 * Representing a collection containing models having external relations.
 */
interface ExternalModelRelatedCollectionContract
{
    /**
     * Load external relations.
     * 
     * @param string $relationNames relation names to load.
     * @return static
     */
    public function loadExternalRelations(...$relationNames): ExternalModelRelatedCollectionContract;

    /**
     * Load external relations only on members that do not have them yet.
     *
     * @param string $relationNames relation names to load if missing.
     * @return static
     */
    public function loadMissingExternalRelations(...$relationNames): ExternalModelRelatedCollectionContract;
}