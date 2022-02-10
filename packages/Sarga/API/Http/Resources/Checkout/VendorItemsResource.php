<?php

namespace Sarga\API\Http\Resources\Checkout;

class VendorItemsResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'shop_title' => $this->shop_title,
            'items' => CartItemResource::collection($this->items),
        ];
    }

}