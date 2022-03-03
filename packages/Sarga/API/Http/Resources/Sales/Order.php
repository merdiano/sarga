<?php

namespace Sarga\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Customer\CustomerResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\InvoiceResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\OrderAddressResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\OrderItemResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\ShipmentResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Setting\ChannelResource;

class Order extends JsonResource
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
            'id'                                 => $this->id,
            'increment_id'                       => $this->increment_id,
            'status'                             => $this->status,
            'status_label'                       => $this->status_label,
            'customer_first_name'                => $this->customer_first_name,
            'customer_last_name'                 => $this->customer_last_name,
            'shipping_method'                    => $this->shipping_method,
            'shipping_title'                     => $this->shipping_title,
            'payment_title'                      => core()->getConfigData('sales.paymentmethods.' . $this->payment->method . '.title'),
            'shipping_description'               => $this->shipping_description,
            'coupon_code'                        => $this->coupon_code,
            'total_item_count'                   => $this->total_item_count,
            'total_qty_ordered'                  => $this->total_qty_ordered,
            'grand_total'                        => $this->grand_total,
            'formated_grand_total'               => core()->formatPrice($this->base_grand_total, $this->order_currency_code),
            'grand_total_invoiced'               => $this->grand_total_invoiced,
            'formated_grand_total_invoiced'      => core()->formatPrice($this->base_grand_total_invoiced, $this->order_currency_code),
            'grand_total_refunded'               => $this->grand_total_refunded,
            'formated_grand_total_refunded'      => core()->formatPrice($this->base_grand_total_refunded, $this->order_currency_code),
            'sub_total'                          => $this->sub_total,
            'formated_sub_total'                 => core()->formatPrice($this->base_sub_total, $this->order_currency_code),
            'sub_total_invoiced'                 => $this->sub_total_invoiced,
            'formated_sub_total_invoiced'        => core()->formatPrice($this->base_sub_total_invoiced, $this->order_currency_code),
            'sub_total_refunded'                 => $this->sub_total_refunded,
            'discount_percent'                   => $this->discount_percent,
            'discount_amount'                    => $this->discount_amount,
            'formated_discount_amount'           => core()->formatPrice($this->base_discount_amount, $this->order_currency_code),
            'discount_invoiced'                  => $this->discount_invoiced,
            'formated_discount_invoiced'         => core()->formatPrice($this->base_discount_invoiced, $this->order_currency_code),
            'discount_refunded'                  => $this->discount_refunded,
            'formated_discount_refunded'         => core()->formatPrice($this->base_discount_refunded, $this->order_currency_code),
            'tax_amount'                         => $this->tax_amount,
            'formated_tax_amount'                => core()->formatPrice($this->base_tax_amount, $this->order_currency_code),
            'tax_amount_invoiced'                => $this->tax_amount_invoiced,
            'formated_tax_amount_invoiced'       => core()->formatPrice($this->base_tax_amount_invoiced, $this->order_currency_code),
            'tax_amount_refunded'                => $this->tax_amount_refunded,
            'formated_tax_amount_refunded'       => core()->formatPrice($this->base_tax_amount_refunded, $this->order_currency_code),
            'shipping_amount'                    => $this->shipping_amount,
            'formated_shipping_amount'           => core()->formatPrice($this->base_shipping_amount, $this->order_currency_code),
            'shipping_invoiced'                  => $this->shipping_invoiced,
            'formated_shipping_invoiced'         => core()->formatPrice($this->base_shipping_invoiced, $this->order_currency_code),
            'shipping_refunded'                  => $this->shipping_refunded,
            'formated_shipping_refunded'         => core()->formatPrice($this->base_shipping_refunded, $this->order_currency_code),
            'customer'                           => $this->when($this->customer_id, new CustomerResource($this->customer)),
            'shipping_address'                   => new OrderAddressResource($this->shipping_address),
            'billing_address'                    => new OrderAddressResource($this->billing_address),
            'items'                              => OrderItemResource::collection($this->items),
            'invoices'                           => InvoiceResource::collection($this->invoices),
            'shipments'                          => ShipmentResource::collection($this->shipments),
            'updated_at'                         => $this->updated_at,
            'created_at'                         => $this->created_at,
        ];
    }
}