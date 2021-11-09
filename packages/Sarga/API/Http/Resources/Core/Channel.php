<?php

namespace Sarga\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\Category;


class Channel extends JsonResource
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
            'id'                => $this->id,
            'code'              => $this->code,
            'name'              => $this->name,
            'hostname'          => $this->hostname,
            'root_category_id'  => $this->root_category_id,
            'promotion_category_id'  => $this->promotion_category_id,
            'is_maintenance_on' => $this->is_maintenance_on,
            'sliders'           => Slider::collection($this->sliders),
            'brand_attribute_id' => 25 //todo vremenno goyuldy. id admindan bazadan settingsden almaly(2 marketplace goshulanda)
//            'root_category'     => $this->when($this->root_category_id, new CategoryResource($this->root_category)),
//            'main_categories' => $this->when(request()->has('channel_id'),Category::collection($this->categories))

        ];
    }
}