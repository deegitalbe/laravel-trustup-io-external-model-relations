<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\Relations;

use Illuminate\Support\Collection;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Contracts\Models\ExternalModelContract;

/**
 * Representing callback used to actually load a relation.
 */
interface ExternalModelRelationLoadingCallbackContract
{
    /**
     * Loading external models based on given identifiers collection.
     * 
     * @param Collection<int, string|int> $identifiers
     * @return Collection<int, ExternalModelContract>
     */
    public function load(Collection $identifiers): Collection;
}