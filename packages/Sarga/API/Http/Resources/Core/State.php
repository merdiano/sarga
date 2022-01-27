<?php

namespace Sarga\API\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

class State extends JsonResource
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
            'id'   => $this->id,
            'code' => $this->code,
            'name' => $this->default_name
        ];
    }
}