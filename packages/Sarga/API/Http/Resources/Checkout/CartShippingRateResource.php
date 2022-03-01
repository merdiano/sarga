<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Checkout\Facades\Cart;

class CartShippingRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
//            'id'                  => $this->id,
            'carrier'             => $this->carrier,
            'carrier_title'       => $this->carrier_title,
            'method'              => $this->method,
            'method_title'        => $this->method_title,
            'method_description'  => $this->method_description,
            'price'               => $this->price,
            'formatted_price'      => core()->formatPrice($this->base_price),

        ];
    }
}
