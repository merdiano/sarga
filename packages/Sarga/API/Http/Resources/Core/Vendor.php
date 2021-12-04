<?php

namespace Sarga\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'categories' => json_decode($this->categories),
        ];
    }
}