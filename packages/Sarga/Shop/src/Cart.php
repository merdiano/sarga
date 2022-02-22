<?php

namespace Sarga\Shop;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Tax\Helpers\Tax;

class Cart extends \Webkul\Checkout\Cart
{
    /**
     * Updates cart totals.
     *
     * @return void
     */
    public function collectTotals(): void
    {
        if (! $this->validateItems()) {
            return;
        }

        if (! $cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $cart);

        $this->calculateItemsTax();
        $cart->refresh();

        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->tax_total = $cart->base_tax_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;

        foreach ($cart->items as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;

            $cart->sub_total = (float)$cart->sub_total + $item->total;
            $cart->base_sub_total = (float)$cart->base_sub_total + $item->base_total;
        }

        $cart->tax_total = Tax::getTaxTotal($cart, false);
        $cart->base_tax_total = Tax::getTaxTotal($cart, true);

        $cart->grand_total = $cart->sub_total + $cart->tax_total - $cart->discount_amount;
        $cart->base_grand_total = $cart->base_sub_total + $cart->base_tax_total - $cart->base_discount_amount;

        if ($shipping = $cart->selected_shipping_rate) {
            $cart->grand_total = (float) $cart->grand_total + $shipping->price - $shipping->discount_amount;
            $cart->base_grand_total = (float) $cart->base_grand_total + $shipping->base_price - $shipping->base_discount_amount;

            $cart->discount_amount += $shipping->discount_amount;
            $cart->base_discount_amount += $shipping->base_discount_amount;
        }

        $cart = $this->finalizeCartTotals($cart);

        $quantities = 0;

        foreach ($cart->items as $item) {
            $quantities = $quantities + $item->quantity;
        }

        $cart->items_count = $cart->items->count();

        $cart->items_qty = $quantities;

        $cart->cart_currency_code = core()->getCurrentCurrencyCode();

        $cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $cart);
    }

    /**
     * Round cart totals.
     *
     * @param \Webkul\Checkout\Models\Cart $cart
     * @return \Webkul\Checkout\Models\Cart
     */
    private function finalizeCartTotals(CartModel $cart): CartModel
    {
        $cart->discount_amount = round($cart->discount_amount, 2);
        $cart->base_discount_amount = round($cart->base_discount_amount, 2);

        $cart->sub_total = round($cart->sub_total, 2);
        $cart->base_sub_total = round($cart->base_sub_total, 2);

        $cart->grand_total = round($cart->grand_total, 2);
        $cart->base_grand_total = round($cart->base_grand_total, 2);

        $cart->weight_total = round($cart->grand_total, 2);
        $cart->base_weight_total = round($cart->base_grand_total, 2);

        return $cart;
    }
}