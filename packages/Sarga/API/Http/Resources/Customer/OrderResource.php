<?php

namespace Sarga\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Customer\CustomerResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\InvoiceResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\OrderAddressResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\OrderItemResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\ShipmentResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Setting\ChannelResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
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
            'is_gift'                            => $this->is_gift,
            'total_item_count'                   => (int)$this->total_item_count,
            'total_qty_ordered'                  => (int)$this->total_qty_ordered,
            'base_currency_code'                 => $this->base_currency_code,
            'channel_currency_code'              => $this->channel_currency_code,
            'order_currency_code'                => $this->order_currency_code,
            'grand_total'                        => (double)$this->grand_total,
            'formated_grand_total'               => core()->formatPrice($this->grand_total, $this->order_currency_code),
            'base_grand_total'                   => (double)$this->base_grand_total,
            'formated_base_grand_total'          => core()->formatBasePrice($this->base_grand_total),
            'grand_total_invoiced'               => (double)$this->grand_total_invoiced,
            'formated_grand_total_invoiced'      => core()->formatPrice($this->grand_total_invoiced, $this->order_currency_code),
            'base_grand_total_invoiced'          => (double)$this->base_grand_total_invoiced,
            'formated_base_grand_total_invoiced' => core()->formatBasePrice($this->base_grand_total_invoiced),
            'grand_total_refunded'               => (double)$this->grand_total_refunded,
            'formated_grand_total_refunded'      => core()->formatPrice($this->grand_total_refunded, $this->order_currency_code),
            'base_grand_total_refunded'          => (double)$this->base_grand_total_refunded,
            'formated_base_grand_total_refunded' => core()->formatBasePrice($this->base_grand_total_refunded),
            'sub_total'                          => (double)$this->sub_total,
            'formated_sub_total'                 => core()->formatPrice($this->sub_total, $this->order_currency_code),
            'base_sub_total'                     => (double)$this->base_sub_total,
            'formated_base_sub_total'            => core()->formatBasePrice($this->base_sub_total),
            'sub_total_invoiced'                 => (double)$this->sub_total_invoiced,
            'formated_sub_total_invoiced'        => core()->formatPrice($this->sub_total_invoiced, $this->order_currency_code),
            'base_sub_total_invoiced'            => (double)$this->base_sub_total_invoiced,
            'formated_base_sub_total_invoiced'   => core()->formatBasePrice($this->base_sub_total_invoiced),
            'sub_total_refunded'                 => (double)$this->sub_total_refunded,
            'formated_sub_total_refunded'        => core()->formatPrice($this->sub_total_refunded, $this->order_currency_code),
            'discount_percent'                   => (double)$this->discount_percent,
            'discount_amount'                    => (double)$this->discount_amount,
            'formated_discount_amount'           => core()->formatPrice($this->discount_amount, $this->order_currency_code),
            'base_discount_amount'               => (double)$this->base_discount_amount,
            'formated_base_discount_amount'      => core()->formatBasePrice($this->base_discount_amount),
            'discount_invoiced'                  => (double)$this->discount_invoiced,
            'formated_discount_invoiced'         => core()->formatPrice($this->discount_invoiced, $this->order_currency_code),
            'base_discount_invoiced'             => (double)$this->base_discount_invoiced,
            'formated_base_discount_invoiced'    => core()->formatBasePrice($this->base_discount_invoiced),
            'discount_refunded'                  => (double)$this->discount_refunded,
            'formated_discount_refunded'         => core()->formatPrice($this->discount_refunded, $this->order_currency_code),
            'base_discount_refunded'             => (double)$this->base_discount_refunded,
            'formated_base_discount_refunded'    => core()->formatBasePrice($this->base_discount_refunded),
            'tax_amount'                         => (double)$this->tax_amount,
            'formated_tax_amount'                => core()->formatPrice($this->tax_amount, $this->order_currency_code),
            'base_tax_amount'                    => (double)$this->base_tax_amount,
            'formated_base_tax_amount'           => core()->formatBasePrice($this->base_tax_amount),
            'tax_amount_invoiced'                => (double)$this->tax_amount_invoiced,
            'formated_tax_amount_invoiced'       => core()->formatPrice($this->tax_amount_invoiced, $this->order_currency_code),
            'base_tax_amount_invoiced'           => (double)$this->base_tax_amount_invoiced,
            'formated_base_tax_amount_invoiced'  => core()->formatBasePrice($this->base_tax_amount_invoiced),
            'tax_amount_refunded'                => (double)$this->tax_amount_refunded,
            'formated_tax_amount_refunded'       => core()->formatPrice($this->tax_amount_refunded, $this->order_currency_code),
            'base_tax_amount_refunded'           => (double)$this->base_tax_amount_refunded,
            'formated_base_tax_amount_refunded'  => core()->formatBasePrice($this->base_tax_amount_refunded),
            'shipping_amount'                    => (double)$this->shipping_amount,
            'formated_shipping_amount'           => core()->formatPrice($this->shipping_amount, $this->order_currency_code),
            'base_shipping_amount'               => (double)$this->base_shipping_amount,
            'formated_base_shipping_amount'      => core()->formatBasePrice($this->base_shipping_amount),
            'shipping_invoiced'                  => $this->shipping_invoiced,
            'formated_shipping_invoiced'         => core()->formatPrice($this->shipping_invoiced, $this->order_currency_code),
            'base_shipping_invoiced'             => $this->base_shipping_invoiced,
            'formated_base_shipping_invoiced'    => core()->formatBasePrice($this->base_shipping_invoiced),
            'shipping_refunded'                  => $this->shipping_refunded,
            'formated_shipping_refunded'         => core()->formatPrice($this->shipping_refunded, $this->order_currency_code),
            'base_shipping_refunded'             => $this->base_shipping_refunded,
            'formated_base_shipping_refunded'    => core()->formatBasePrice($this->base_shipping_refunded),
            'customer'                           => $this->when($this->customer_id, new CustomerResource($this->customer)),
            'channel'                            => $this->when($this->channel_id, new ChannelResource($this->channel)),
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