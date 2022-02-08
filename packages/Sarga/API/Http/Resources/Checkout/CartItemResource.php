<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Sarga\API\Http\Resources\Catalog\Product as ProductResource;
use Webkul\Marketplace\Repositories\ProductRepository;

class CartItemResource extends JsonResource
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
    public function toArray($request)
    {
        $seller = $this->sellerProductRepository->getSellerByProductId($this->product_id);
        return [
            'id'                            => $this->id,
            'vendor'                        => $this->when($seller,$seller->shop_title),
            'quantity'                      => $this->quantity,
            'name'                          => $this->name,
            'base_total_weight'             => $this->base_total_weight,
            'price'                         => $this->price,
            'formatted_price'               => core()->formatPrice($this->price, $this->cart->cart_currency_code),
            'base_price'                    => $this->base_price,
            'formatted_base_price'          => core()->formatBasePrice($this->base_price),
            'custom_price'                  => $this->custom_price,
            'formatted_custom_price'        => core()->formatPrice($this->custom_price, $this->cart->cart_currency_code),
            'total'                         => $this->total,
            'formatted_total'               => core()->formatPrice($this->total, $this->cart->cart_currency_code),
            'base_total'                    => $this->base_total,
            'formatted_base_total'          => core()->formatBasePrice($this->base_total),
            'tax_percent'                   => $this->tax_percent,
            'tax_amount'                    => $this->tax_amount,
            'formatted_tax_amount'          => core()->formatPrice($this->tax_amount, $this->cart->cart_currency_code),
            'base_tax_amount'               => $this->base_tax_amount,
            'formatted_base_tax_amount'     => core()->formatBasePrice($this->base_tax_amount),
            'discount_percent'              => $this->discount_percent,
            'discount_amount'               => $this->discount_amount,
            'formatted_discount_amount'     => core()->formatPrice($this->discount_amount, $this->cart->cart_currency_code),
            'base_discount_amount'          => $this->base_discount_amount,
            'formatted_base_discount_amount'=> core()->formatBasePrice($this->base_discount_amount),
            'additional'                    => is_array($this->resource->additional)
                ? $this->resource->additional
                : json_decode($this->resource->additional, true),
            'child'                         => new self($this->child),
            'product'                       => $this->when($this->product_id, new ProductResource($this->product)),
        ];
    }
}
