<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImage extends JsonResource
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
            'path'               => $this->path,
            'original_image_url' => $this->url,
            'small_image_url'    => url('cache/small/' . $this->path),
        ];
    }
}