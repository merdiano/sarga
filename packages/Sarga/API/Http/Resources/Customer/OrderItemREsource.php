<?php

namespace Sarga\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemREsource extends JsonResource
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
            'product_id'                        => $this->product_id,
            'coupon_code'                       => $this->coupon_code,
            'weight'                            => (double) $this->weight,
            'total_weight'                      => $this->total_weight,
            'qty_ordered'                       => (int) $this->qty_ordered,
            'qty_canceled'                      => (int) $this->qty_canceled,
            'qty_invoiced'                      => (int) $this->qty_invoiced,
            'qty_shipped'                       => (int) $this->qty_shipped,
            'qty_refunded'                      => (int) $this->qty_refunded,
            'price'                             => (double) $this->price,
            'formated_price'                    => core()->formatPrice($this->base_price, $this->order->order_currency_code),
            'total'                             => $this->total,
            'formated_total'                    => core()->formatPrice($this->base_total, $this->order->order_currency_code),

            'total_invoiced'                    => $this->total_invoiced,
            'formated_total_invoiced'           => core()->formatPrice($this->base_total_invoiced, $this->order->order_currency_code),

            'amount_refunded'                   => $this->amount_refunded,
            'formated_amount_refunded'          => core()->formatPrice($this->base_amount_refunded, $this->order->order_currency_code),

            'discount_percent'                  => $this->discount_percent,
            'discount_amount'                   => (double) $this->discount_amount,
            'formated_discount_amount'          => core()->formatPrice($this->base_discount_amount, $this->order->order_currency_code),

            'discount_invoiced'                 => $this->discount_invoiced,
            'formated_discount_invoiced'        => core()->formatPrice($this->base_discount_invoiced, $this->order->order_currency_code),

            'discount_refunded'                 => $this->discount_refunded,
            'formated_discount_refunded'        => core()->formatPrice($this->base_discount_refunded, $this->order->order_currency_code),

            'grant_total'                       => $this->total + $this->tax_amount,
            'formated_grant_total'              => core()->formatPrice($this->base_total + $this->base_tax_amount, $this->order->order_currency_code),
            'downloadable_links'                => $this->downloadable_link_purchased,
            'additional'                        => is_array($this->resource->additional)
                ? $this->resource->additional
                : json_decode($this->resource->additional, true),
            'child'                             => new self($this->child),
            'children'                          => Self::collection($this->children),
        ];
    }
}