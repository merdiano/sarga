<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'filter'      => $this->filter,
            'description' => $this->description,
            'categories'  => Category::collection($this->categories),
            'brands'      => Brands::collection($this->brands)

        ];
    }

}