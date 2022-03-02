<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Webkul\Marketplace\Repositories\ProductRepository;

class CartItemResource extends JsonResource
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
            'id'                            => $this->id,
            'quantity'                      => $this->quantity,
            'name'                          => $this->name,
            'base_total_weight'             => $this->base_total_weight,
            'price'                         => $this->price,
            'formatted_price'               => core()->formatPrice($this->base_price, $this->cart->cart_currency_code),
            'custom_price'                  => $this->custom_price,
            'formatted_custom_price'        => core()->formatPrice($this->custom_price, $this->cart->cart_currency_code),
            'total'                         => $this->total,
            'formatted_total'               => core()->formatPrice($this->base_total, $this->cart->cart_currency_code),
            'tax_percent'                   => $this->tax_percent,
            'tax_amount'                    => $this->tax_amount,
            'formatted_tax_amount'          => core()->formatPrice($this->base_tax_amount, $this->cart->cart_currency_code),
            'discount_percent'              => $this->discount_percent,
            'discount_amount'               => $this->discount_amount,
            'formatted_discount_amount'     => core()->formatPrice($this->base_discount_amount, $this->cart->cart_currency_code),
            'additional'                    => is_array($this->resource->additional)
                ? $this->resource->additional
                : json_decode($this->resource->additional, true),
            'child'                         => new self($this->child),
            'product'                       => $this->when($this->product_id, new CartItemProduct($this->product)),
        ];
    }
}
