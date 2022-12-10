<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models;

/**
 * Representing an external model.
 */
interface ExternalModelContract
{
    /**
     * Getting external relation identifier.
     * 
     * @return string|int
     */
    public function getExternalRelationIdentifier(): string|int;
}