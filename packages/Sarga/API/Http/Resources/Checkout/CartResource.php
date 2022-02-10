<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
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
        $taxes = Tax::getTaxRatesWithAmount($this, false);
        $baseTaxes = Tax::getTaxRatesWithAmount($this, true);

        $formatedTaxes = $this->formatTaxAmounts($taxes, false);
        $formatedBaseTaxes = $this->formatTaxAmounts($baseTaxes, true);

        return [
            'id'                                 => $this->id,
            'shipping_method'                    => $this->shipping_method,
            'coupon_code'                        => $this->coupon_code,
            'is_gift'                            => $this->is_gift,
            'items_count'                        => $this->items_count,
            'items_qty'                          => $this->items_qty,
            'grand_total'                        => $this->grand_total,
            'formatted_grand_total'              => core()->formatPrice($this->grand_total, $this->cart_currency_code),
            'base_grand_total'                   => $this->base_grand_total,
            'formatted_base_grand_total'         => core()->formatBasePrice($this->base_grand_total),
            'sub_total'                          => $this->sub_total,
            'formatted_sub_total'                => core()->formatPrice($this->sub_total, $this->cart_currency_code),
            'base_sub_total'                     => $this->base_sub_total,
            'formatted_base_sub_total'           => core()->formatBasePrice($this->base_sub_total),
            'tax_total'                          => $this->tax_total,
            'formatted_tax_total'                => core()->formatPrice($this->tax_total, $this->cart_currency_code),
            'base_tax_total'                     => $this->base_tax_total,
            'formatted_base_tax_total'           => core()->formatBasePrice($this->base_tax_total),
            'discount'                           => $this->discount_amount,
            'formatted_discount'                 => core()->formatPrice($this->discount_amount, $this->cart_currency_code),
            'base_discount'                      => $this->base_discount_amount,
            'formatted_base_discount'            => core()->formatBasePrice($this->base_discount_amount),
            'checkout_method'                    => $this->checkout_method,
            'vendors'                            => $this->groupByVendors($this->items),
            'selected_shipping_rate'             => new CartShippingRateResource($this->selected_shipping_rate),
            'payment'                            => new CartPaymentResource($this->payment),
            'billing_address'                    => new AddressResource($this->billing_address),
            'shipping_address'                   => new AddressResource($this->shipping_address),
            'taxes'                              => json_encode($taxes, JSON_FORCE_OBJECT),
            'formatted_taxes'                    => json_encode($formatedTaxes, JSON_FORCE_OBJECT),
            'base_taxes'                         => json_encode($baseTaxes, JSON_FORCE_OBJECT),
            'formatted_base_taxes'               => json_encode($formatedBaseTaxes, JSON_FORCE_OBJECT),
            'formatted_discounted_sub_total'     => core()->formatPrice($this->sub_total - $this->discount_amount, $this->cart_currency_code),
            'formatted_base_discounted_sub_total'=> core()->formatPrice($this->base_sub_total - $this->base_discount_amount, $this->cart_currency_code),
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
            $data[$seller->shop_title ?? 'default'][] = CartItemResource::make($item);
        }
        return $data;
    }
}
