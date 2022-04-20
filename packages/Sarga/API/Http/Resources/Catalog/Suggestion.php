<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Suggestion extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->product_id ?? $this->id,
            'type'        => $this->suggestion_type,
            'name'        => $this->name,
        ];
    }
}