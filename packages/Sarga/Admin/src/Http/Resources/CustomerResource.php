<?php

namespace Sarga\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'fullname' => $this->name,
            'phone' => $this->phone,
            'token' => env('INTEGRATION_SECRET')
        ];
    }

}