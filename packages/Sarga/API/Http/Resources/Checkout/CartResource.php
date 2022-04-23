<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Sarga\API\Http\Resources\Customer\AddressResource;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Tax\Helpers\Tax;

class CartResource extends JsonResource
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
    public function toArray($request): array
    {

        return [
            'id'                                 => $this->id,
            'shipping_method'                    => $this->shipping_method,
            'shipping_price'                     => $this->selected_shipping_rate->price ?? 0,
            'formatted_shipping_price'           => core()->formatPrice($this->selected_shipping_rate->base_price ?? 0, $this->cart_currency_code),
            'coupon_code'                        => $this->coupon_code,
            'is_gift'                            => $this->is_gift,
            'items_count'                        => (int) $this->items_count,
            'items_qty'                          => (int) $this->items_qty,
            'grand_total'                        => (double) $this->grand_total,
            'formatted_grand_total'              => core()->formatPrice($this->base_grand_total,$this->cart_currency_code),
            'sub_total'                          => (double) $this->sub_total,
            'formatted_sub_total'                => core()->formatPrice($this->base_sub_total, $this->cart_currency_code),
            'tax_total'                          => (double) $this->tax_total,
            'formatted_tax_total'                => core()->formatPrice($this->base_tax_total, $this->cart_currency_code),
            'discount'                           => (double) $this->discount_amount,
            'formatted_discount'                 => core()->formatPrice(abs((double)$this->base_discount_amount), $this->cart_currency_code),
            'checkout_method'                    => $this->checkout_method,
            'vendors'                            => $this->groupByVendors($this->items),
            'payment'                            => new CartPaymentResource($this->payment),
            'billing_address'                    => new AddressResource($this->billing_address),
            'shipping_address'                   => new AddressResource($this->shipping_address),
            'formatted_discounted_sub_total'     => core()->formatPrice($this->base_sub_total - $this->base_discount_amount, $this->cart_currency_code)
        ];
    }

    /**
     * Format tax amounts.
     *
     * @param  array  $taxes
     * @param  bool  $isBase
     * @return array
     */
    private function formatTaxAmounts(array $taxes, bool $isBase = false): array
    {
        $result = [];

        foreach ($taxes as $taxRate => $taxAmount) {
            if ($isBase === true) {
                $result[$taxRate] = core()->formatBasePrice($taxAmount);
            } else {
                $result[$taxRate] = core()->formatPrice($taxAmount, $this->cart_currency_code);
            }
        }

        return $result;
    }

    private function groupByVendors($items){
        $data = array();
        foreach($items as $item){
            $seller = $this->sellerProductRepository->getSellerByProductId($item->product_id);
            $data[$seller->shop_title ?? 'trendyol'][] = CartItemResource::make($item);
        }
        return $data;
    }
}
