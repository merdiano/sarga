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
            'parent_id'          => $this->parent_id,
//            'code'               => $this->code,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'trendyol_url'               => $this->trendyol_url,
            'lcw_url'               => $this->lcw_url,
            'display_mode'       => $this->display_mode,
//            'description'        => $this->description,
//            'status'             => $this->status,
            'image_url'          => $this->image_url,
            'category_icon_path' => $this->category_icon_path
                ? Storage::url($this->category_icon_path)
                : null,
//            'additional'         => is_array($this->resource->additional)
//                ? $this->resource->additional
//                : json_decode($this->resource->additional, true),
            $this->mergeWhen($this->showChildren(), [
                'children' => Category::collection($this->children),
            ])
        ];
    }

    private function showChildren(){
        switch (request()->route()->getName()){
            case 'api.descendant-categories':
            case 'api.vendors': return true;
            default : return false;
        }
    }
}
