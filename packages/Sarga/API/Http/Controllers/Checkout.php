<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Sarga\API\Http\Resources\Checkout\CartResource;
use Sarga\API\Http\Resources\Checkout\CartShippingRateResource;
use Sarga\API\Http\Resources\Checkout\PickupAddress;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Payment\Facades\Payment;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\CheckoutController;
use Webkul\RestApi\Http\Resources\V1\Shop\Sales\OrderResource;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shipping\Facades\Shipping;

class Checkout extends CheckoutController
{
    public function index(){
        if (Cart::hasError()){
            return response([
                'success' => false,
                'message' => 'Korzina ustarel. Pozhaluysta obnavite korzinu'

            ],400);
        }

        $rates = [];

        Shipping::collectRates();

        foreach (Shipping::getGroupedAllShippingRates() as $code => $shippingMethod) {
            $rates[] = [
                'carrier_title' => $shippingMethod['carrier_title'],
                'rates'         => CartShippingRateResource::collection(collect($shippingMethod['rates'])),
            ];
        }

        $addresses = core()->getCurrentChannel()->inventory_sources()->get();
        return response([
            'shipping_rates' => $rates,
            'pickup_addresses' => PickupAddress::collection($addresses),
            'payment_methods' => Payment::getPaymentMethods()
        ]);
    }

    public function saveOrder(OrderRepository $orderRepository){
        //save address
        $addresses['billing'] = request()->get('billing');
        $addresses['shipping'] = request()->get('shipping');

        $addresses['billing']['address1'] = implode(PHP_EOL, array_filter($addresses['billing']['address1']));

        $addresses['shipping']['address1'] = implode(PHP_EOL, array_filter($addresses['shipping']['address1']));

        if (isset($addresses['billing']['id']) && str_contains($addresses['billing']['id'], 'address_')) {
            unset($addresses['billing']['id']);
            unset($addresses['billing']['address_id']);
        }

        if (isset($addresses['shipping']['id']) && Str::contains($addresses['shipping']['id'], 'address_')) {
            unset($addresses['shipping']['id']);
            unset($addresses['shipping']['address_id']);
        }

        $shippingMethod = request()->get('shipping_method');
        $payment = request()->get('payment');

        if (Cart::hasError() ||
            ! Cart::saveCustomerAddress($addresses) ||
            ! Cart::saveShippingMethod($shippingMethod) ||
            ! Cart::savePaymentMethod($payment)) {
            abort(400);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response([
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        return response([
            'data'    => [
                'order' => new OrderResource($order),
            ],
            'message' => 'Order saved successfully.',
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