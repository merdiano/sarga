<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VendorCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'display_mode'       => $this->display_mode,
            'image_url'          => $this->image_url,
            'children' => Category::collection($this->children),
        ];
    }
}