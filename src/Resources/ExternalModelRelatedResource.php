<?php
namespace Deegitalbe\LaravelTrustupIoExternalModelRelations\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Deegitalbe\LaravelTrustupIoExternalModelRelations\Traits\Resources\IsExternalModelRelatedResource;

/**
 * Representing an resource having external relations
 */
class ExternalModelRelatedResource extends JsonResource
{
    use IsExternalModelRelatedResource;
}