<?php

namespace Sarga\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\InvoiceResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\OrderAddressResource;
use Webkul\RestApi\Http\Resources\V1\Admin\Sale\ShipmentResource;

class OrderResource extends JsonResource
{
    public function __construct($resource)
    {
        $this->sellerProductRepository = app(ProductRepository::class);
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        return [
            'id'                                 => $this->id,
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
            'total_item_count'                   => (int) $this->total_item_count,
            'total_qty_ordered'                  => (int) $this->total_qty_ordered,
            'base_currency_code'                 => $this->base_currency_code,
            'channel_currency_code'              => $this->channel_currency_code,
            'order_currency_code'                => $this->order_currency_code,
            'grand_total'                        => (double) $this->grand_total,
            'formatted_grand_total'               => core()->formatPrice($this->base_grand_total, $this->order_currency_code),

            'grand_total_invoiced'               => (double) $this->grand_total_invoiced,
            'formatted_grand_total_invoiced'      => core()->formatPrice($this->base_grand_total_invoiced, $this->order_currency_code),

            'grand_total_refunded'               => (double) $this->grand_total_refunded,
            'formatted_grand_total_refunded'      => core()->formatPrice($this->base_grand_total_refunded, $this->order_currency_code),

            'sub_total'                          => (double) $this->sub_total,
            'formatted_sub_total'                 => core()->formatPrice($this->base_sub_total, $this->order_currency_code),

            'sub_total_invoiced'                 => (double) $this->sub_total_invoiced,
            'formatted_sub_total_invoiced'        => core()->formatPrice($this->base_sub_total_invoiced, $this->order_currency_code),

            'sub_total_refunded'                 => (double) $this->sub_total_refunded,
//            'formatted_sub_total_refunded'        => core()->formatPrice($this->sub_total_refunded, $this->order_currency_code),
            'discount_percent'                   => (double) $this->discount_percent,
            'discount_amount'                    => (double) $this->discount_amount,
            'formatted_discount_amount'           => core()->formatPrice($this->base_discount_amount, $this->order_currency_code),

            'discount_invoiced'                  => (double)$this->discount_invoiced,
            'formatted_discount_invoiced'         => core()->formatPrice($this->base_discount_invoiced, $this->order_currency_code),

            'discount_refunded'                  => (double) $this->discount_refunded,
            'formatted_discount_refunded'         => core()->formatPrice($this->base_discount_refunded, $this->order_currency_code),

            'shipping_amount'                    => (double) $this->shipping_amount,
            'formatted_shipping_amount'           => core()->formatPrice($this->base_shipping_amount, $this->order_currency_code),

            'shipping_invoiced'                  => $this->shipping_invoiced,
            'formatted_shipping_invoiced'         => core()->formatPrice($this->base_shipping_invoiced, $this->order_currency_code),

            'shipping_refunded'                  => $this->shipping_refunded,
            'formatted_shipping_refunded'         => core()->formatPrice($this->base_shipping_refunded, $this->order_currency_code),

            'shipping_address'                   => new OrderAddressResource($this->shipping_address),
            'billing_address'                    => new OrderAddressResource($this->billing_address),
            'vendors'                            => $this->groupByVendors($this->items),
            'invoices'                           => InvoiceResource::collection($this->invoices),
            'shipments'                          => ShipmentResource::collection($this->shipments),
            'updated_at'                         => $this->updated_at,
            'created_at'                         => $this->created_at,
        ];
    }

    private function groupByVendors($items){
        $data = array();
        foreach($items as $item){
            $seller = $this->sellerProductRepository->getSellerByProductId($item->product_id);
            $data[$seller->shop_title ?? 'outlet'][] = OrderItemResource::make($item);
        }
        return $data;
    }
}