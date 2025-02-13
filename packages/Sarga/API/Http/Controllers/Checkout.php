<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Sarga\API\Http\Resources\Checkout\CartResource;
use Sarga\API\Http\Resources\Checkout\PickupAddress;
use Sarga\API\Http\Resources\Customer\OrderResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Facades\Payment;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\CheckoutController;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Shipping\Facades\Shipping;


class Checkout extends CheckoutController
{
    public function index(){
        if (Cart::hasError()){
            return response([
                'success' => false,
                'message' => 'Refresh cart'

            ],400);
        }
        //inventory source goes as shipping addresses
        $addresses = core()->getCurrentChannel()->inventory_sources()->get();
        return response([
            'shipping' => Shipping::getShippingMethods(),
            'pickup_addresses' => PickupAddress::collection($addresses),
            'payment' => Payment::getPaymentMethods(),
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

        $shippingMethod = $request->get('shipping_method');
        if (Cart::hasError() || ! Cart::saveCustomerAddress($data) || ! Shipping::collectRates()
            || ! $shippingMethod
            || ! Cart::saveShippingMethod($shippingMethod)) {
            return response(['message'=>'error. wrong shipment method or address'],400);
        }

        Cart::collectTotals();

        return response([
            'cart'    => new CartResource(Cart::getCart()),
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
            return response([
                'success' => false,
                'message' => 'Payment unsuccessful'

            ],400);
        }
        Cart::collectTotals();
        return response([
            'cart' => new CartResource(Cart::getCart()),
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
    /**
     * Save order.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(OrderRepository $orderRepository)
    {
        if (Cart::hasError()) {
            abort(400);
        }

        Cart::collectTotals();

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if(! $status = Cart::checkMinimumOrder()){
            return response([
                'data'    => [
                    'cart'   => new CartResource(Cart::getCart()),
                    'status' => $status,
                ],
                'message' =>  __('rest-api::app.checkout.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]),
            ]);
        }

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
                'status' => $status
            ],
            'message' => 'Order saved successfully.',
        ]);
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    protected function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (! $cart->checkMinimumOrder()) {
            throw new \Exception(__('rest-api::app.checkout.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new \Exception(__('rest-api::app.checkout.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(__('rest-api::app.checkout.check-billing-address'));
        }

        if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
            throw new \Exception(__('rest-api::app.checkout.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(__('rest-api::app.checkout.specify-payment-method'));
        }
    }
}