<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Sarga\API\Http\Resources\Catalog\ProductVariant;

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
            'quantity'                      => (int)$this->quantity,
            'name'                          => $this->name,
            'base_total_weight'             => (double)$this->base_total_weight,
            'price'                         => (double)$this->price,
            'formatted_price'               => core()->formatPrice($this->base_price, $this->cart->cart_currency_code),
            'custom_price'                  => (double)$this->custom_price,
            'total'                         => (double)$this->total,
            'formatted_total'               => core()->formatPrice($this->base_total, $this->cart->cart_currency_code),
            'discount_percent'              => $this->discount_percent,
            'discount_amount'               => (double)$this->discount_amount,
            'formatted_discount_amount'     => core()->formatPrice($this->base_discount_amount, $this->cart->cart_currency_code),
            'additional'                    => is_array($this->resource->additional)
                ? $this->resource->additional
                : json_decode($this->resource->additional, true),
            'child'                         => new self($this->child),
            'product'                       => $this->when($this->product_id, new ProductVariant($this->product->parent->super_attributes??null))
        ];
    }
}
