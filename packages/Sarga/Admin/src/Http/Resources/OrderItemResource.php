<?php

namespace Sarga\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'app_order_id' => $this->order_id,
            'ty_item_number',
            'quantity' => $this->qty_order_id,
            'price'   => $this->total,
            'order_date' => $this->created_at,
        ];
    }
}