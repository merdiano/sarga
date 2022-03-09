<?php

namespace Sarga\Shop;

use Sarga\Shop\Repositories\RecipientRepository;
use Webkul\Checkout\Models\CartAddress;

class ShoppingCart extends \Webkul\Checkout\Cart
{

    public function saveAddress($address, $shipment) : bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        if($shipment !== 'pickup_pickup'){
            $this->saveMusteriAddress($address,$cart);
        }
        else
            $this->saveRecipientAddress($address,$cart);

        $this->assignCustomerFields($cart);

        return $cart->save();
    }

    /**
     * Save customer address.
     *
     * @param  array  $data
     * @return bool
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    private function saveMusteriAddress($data,$cart)
    {
        $billingAddressData = $this->gatherBillingAddress($data, $cart);

        $shippingAddressData = $this->gatherShippingAddress($data, $cart);

        $this->linkAddresses($cart, $billingAddressData, $shippingAddressData);

    }

    public function saveRecipientAddress($data,$cart)
    {

        $recipientAddressRepository = app(RecipientRepository::class);

        $customerAddress = [];

        if (isset($data['billing']['address_id']) && $data['billing']['address_id'])
        {
            $customerAddress = $recipientAddressRepository->findOneWhere(['id' => $data['billing']['address_id']])
                ->toArray();
        }

        $billingAddress = array_merge(
            $customerAddress,
            $data['billing'],
            ['cart_id' => $cart->id, 'customer_id' => $cart->customer_id],
            $this->fillAddressAttributes($data['billing'])
        );

        $this->linkAddresses($cart,$billingAddress,$billingAddress);

    }

    /**
     * Fill address attributes.
     *
     * @return array
     */
    private function fillAddressAttributes(array $addressAttributes): array
    {
        $attributes = [];

        $cartAddress = new CartAddress();

        foreach ($cartAddress->getFillable() as $attribute) {
            if (isset($addressAttributes[$attribute])) {
                $attributes[$attribute] = $addressAttributes[$attribute];
            }
        }

        return $attributes;
    }
}