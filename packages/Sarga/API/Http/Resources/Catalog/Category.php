<?php

namespace Sarga\API\Http\Resources\Catalog;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
//            'code'               => $this->code,
            'name'               => $this->name,
            'slug'               => $this->slug,
//            'display_mode'       => $this->display_mode,
            'description'        => $this->description,
//            'status'             => $this->status,
            'image_url'          => $this->image_url,
            'category_icon_path' => $this->category_icon_path
                ? Storage::url($this->category_icon_path)
                : null,
//            'additional'         => is_array($this->resource->additional)
//                ? $this->resource->additional
//                : json_decode($this->resource->additional, true),
            'children'           => Category::collection($this->children)
        ];
    }
}
