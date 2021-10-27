<?php

namespace Sarga\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

class Slider extends JsonResource
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
            'id'        => $this->id,
            'title'     => $this->title,
            'image_url' => $this->image_url,
            'content'   => $this->content,
            'expired_at'=> $this->expired_at,
            'sort_order'=> $this->sort_order,
            'slider_path'=>$this->slider_path
        ];
    }
}