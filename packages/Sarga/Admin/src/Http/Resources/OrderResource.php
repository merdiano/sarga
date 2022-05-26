<?php

namespace Sarga\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'customer_phone' => $this->customer()->phone,
            'items' => OrderItemResource::collection($this->items),
            'token' => env('INTEGRATION_SECRET')
        ];
    }
}