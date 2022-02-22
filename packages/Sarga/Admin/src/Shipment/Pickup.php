<?php

namespace Sarga\Admin\Shipment;


use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Carriers\AbstractShipping;

class Pickup extends AbstractShipping
{
    /**
     * Shipping method carrier code.
     *
     * @var string
     */
    protected $code = 'pickup';

    /**
     * Shipping method code.
     *
     * @var string
     */
    protected $method = 'pickup_pickup';

    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        return $this->getRate();
    }

    /**
     * Get rate.
     *
     * @return \Webkul\Checkout\Models\CartShippingRate
     */
    public function getRate(): \Webkul\Checkout\Models\CartShippingRate
    {
        $cartShippingRate = new CartShippingRate;

        $cartShippingRate->carrier = $this->getCode();
        $cartShippingRate->carrier_title = $this->getConfigData('title');
        $cartShippingRate->method = $this->getMethod();
        $cartShippingRate->method_title = $this->getConfigData('title');
        $cartShippingRate->method_description = $this->getConfigData('description');
        $cartShippingRate->is_calculate_tax = $this->getConfigData('is_calculate_tax');
        $cartShippingRate->price = 0;
        $cartShippingRate->base_price = 0;

        $cart = Cart::getCart();

        if ($price = $this->getConfigData('weight_price') ) {
            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $cartShippingRate->price += core()->convertPrice($price) * $item->total_weight;
                    $cartShippingRate->base_price += $price * $item->total_weight;
                }
            }
        }

        return $cartShippingRate;
    }
}