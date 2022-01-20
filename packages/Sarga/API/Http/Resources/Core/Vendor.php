<?php

namespace Sarga\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\Category;

class Vendor extends JsonResource
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
            'id'         => $this->id,
            'url'        => $this->url,
            'shop_title' => $this->shop_title,
            'logo'       => $this->logo_url,
            'banner'     => $this->banner_url,
            'brand_id'   => $this->brand_attribute_id,
            $this->mergeWhen(!empty($this->main_categories) && $this->main_categories->count(),[
                'categories' => Category::collection($this->main_categories)
            ])

        ];
    }
}