<?php

namespace Sarga\API\Http\Resources\Core;

use Sarga\API\Http\Resources\Catalog\VendorCategory;

class Source extends \Illuminate\Http\Resources\Json\JsonResource
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
            'shop_title' => $this->shop_title,
            'logo'       => $this->logo_url,
            'banner'     => $this->banner_url,
            $this->mergeWhen(!empty($this->main_categories) && $this->main_categories->count(),[
                'categories' => VendorCategory::collection($this->main_categories)
            ])

        ];
    }
}