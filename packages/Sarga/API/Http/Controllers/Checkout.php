<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sarga\API\Http\Resources\Checkout\CartResource;
use Sarga\API\Http\Resources\Checkout\CartShippingRateResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Payment\Facades\Payment;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\CheckoutController;
use Webkul\Shipping\Facades\Shipping;

class Checkout extends CheckoutController
{
    public function shipments(){
        $rates = [];

        foreach (Shipping::getGroupedAllShippingRates() as $code => $shippingMethod) {
            $rates[] = [
                'carrier_title' => $shippingMethod['carrier_title'],
                'rates'         => CartShippingRateResource::collection(collect($shippingMethod['rates'])),
            ];
        }

        return response([
            'rates' => $rates,
            'pickup_addresses' => core()->getCurrentChannel()->inventory_sources()->all()
        ]);
    }

    /**
     * Save customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = $request->all();

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));

        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (isset($data['billing']['id']) && str_contains($data['billing']['id'], 'address_')) {
            unset($data['billing']['id']);
            unset($data['billing']['address_id']);
        }

        if (isset($data['shipping']['id']) && Str::contains($data['shipping']['id'], 'address_')) {
            unset($data['shipping']['id']);
            unset($data['shipping']['address_id']);
        }

        if (Cart::hasError() || ! Cart::saveCustomerAddress($data) || ! Shipping::collectRates()) {
            abort(400);
        }

        $rates = [];

        foreach (Shipping::getGroupedAllShippingRates() as $code => $shippingMethod) {
            $rates[] = [
                'carrier_title' => $shippingMethod['carrier_title'],
                'rates'         => CartShippingRateResource::collection(collect($shippingMethod['rates'])),
            ];
        }

        Cart::collectTotals();

        return response([
            'data'    => [
                'rates' => $rates,
                'cart'  => new CartResource(Cart::getCart()),
            ],
            'message' => 'Address saved successfully.',
        ]);
    }

    /**
     * Save shipping method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveShipping(Request $request)
    {
        $shippingMethod = $request->get('shipping_method');

        if (Cart::hasError()
            || ! $shippingMethod
            || ! Cart::saveShippingMethod($shippingMethod)
        ) {
            abort(400);
        }

        Cart::collectTotals();

        return response([
            'data'    => [
                'methods' => Payment::getPaymentMethods(),
                'cart'    => new CartResource(Cart::getCart()),
            ],
            'message' => 'Shipping method saved successfully.',
        ]);
    }

    /**
     * Save payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePayment(Request $request)
    {
        $payment = $request->get('payment');

        if (Cart::hasError() || ! $payment || ! Cart::savePaymentMethod($payment)) {
            abort(400);
        }

        return response([
            'data'    => [
                'cart' => new CartResource(Cart::getCart()),
            ],
            'message' => 'Payment method saved successfully.',
        ]);
    }

    /**
     * Check for minimum order.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMinimumOrder()
    {
        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        $status = Cart::checkMinimumOrder();

        return response([
            'data'    => [
                'cart'   => new CartResource(Cart::getCart()),
                'status' => ! $status ? false : true,
            ],
            'message' => ! $status ? __('rest-api::app.checkout.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) : 'Success',
        ]);
    }

}