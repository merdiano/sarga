<?php

namespace Sarga\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Checkout\CartItemProduct;

class OrderItemResource extends JsonResource
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
            'id'                                => $this->id,
            'sku'                               => $this->sku,
            'type'                              => $this->type,
            'name'                              => $this->name,
            'product'                           => $this->when($this->product_id, new CartItemProduct($this->product)),
            'coupon_code'                       => $this->coupon_code,
            'weight'                            => (double) $this->weight,
            'total_weight'                      => $this->total_weight,
            'qty_ordered'                       => (int) $this->qty_ordered,
            'qty_canceled'                      => (int) $this->qty_canceled,
            'qty_invoiced'                      => (int) $this->qty_invoiced,
            'qty_shipped'                       => (int) $this->qty_shipped,
            'qty_refunded'                      => (int) $this->qty_refunded,
            'price'                             => (double) $this->price,
            'formatted_price'                   => core()->formatPrice($this->base_price, $this->order->order_currency_code),
            'total'                             => (double) $this->total,
            'formatted_total'                   => core()->formatPrice($this->base_total, $this->order->order_currency_code),

            'total_invoiced'                    => (double) $this->total_invoiced,
            'formatted_total_invoiced'          => core()->formatPrice($this->base_total_invoiced, $this->order->order_currency_code),

            'amount_refunded'                   => (double) $this->amount_refunded,
            'formatted_amount_refunded'         => core()->formatPrice($this->base_amount_refunded, $this->order->order_currency_code),

            'discount_percent'                  => (double) $this->discount_percent,
            'discount_amount'                   => (double) $this->discount_amount,
            'formatted_discount_amount'         => core()->formatPrice($this->base_discount_amount, $this->order->order_currency_code),

            'discount_invoiced'                 => (double) $this->discount_invoiced,
            'formatted_discount_invoiced'       => core()->formatPrice($this->base_discount_invoiced, $this->order->order_currency_code),

            'discount_refunded'                 => (double) $this->discount_refunded,
            'formatted_discount_refunded'       => core()->formatPrice($this->base_discount_refunded, $this->order->order_currency_code),

            'grant_total'                       => $this->total + $this->tax_amount,
            'formatted_grant_total'             => core()->formatPrice($this->base_total + $this->base_tax_amount, $this->order->order_currency_code),
            'downloadable_links'                => $this->downloadable_link_purchased,
            'additional'                        => is_array($this->resource->additional)
                ? $this->resource->additional
                : json_decode($this->resource->additional, true),
            'child'                             => new self($this->child),
            'children'                          => Self::collection($this->children),
        ];
    }
}