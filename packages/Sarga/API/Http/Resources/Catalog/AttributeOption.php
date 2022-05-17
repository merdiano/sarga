<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeOption extends JsonResource
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
            'id'           => $this->id,
            'admin_name'   => $this->admin_name,
            'label'        => $this->label,
            'swatch_value' => $this->swatch_value,
            'image' => $this->swatch_value_url,
            'attribute_id' => $this->attribute_id
        ];
    }
}