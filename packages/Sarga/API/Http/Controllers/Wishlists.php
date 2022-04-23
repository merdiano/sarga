<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Sarga\API\Http\Resources\Checkout\CartResource;
use Sarga\API\Http\Resources\Customer\WishListResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\WishlistController;


class Wishlists extends WishlistController
{
    /**
     * Get customer wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = $request->user();

        return response([
            'data' => WishListResource::collection($customer->wishlist_items()->get()),
        ]);
    }

    /**
     * Add or remote item from wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addOrRemove(Request $request, $id)
    {
        $customer = $request->user();

        $wishlistItem = $this->wishlistRepository->findOneWhere([
            'channel_id'  => core()->getCurrentChannel()->id,
            'product_id'  => $id,
            'customer_id' => $customer->id,
        ]);

        if ($wishlistItem) {
            $this->wishlistRepository->delete($wishlistItem->id);

            return response([
                'data'    => null,
                'message' => __('sarga-api::app.wishlist.success-remove'),
            ]);
        }

        $wishlistItem = $this->wishlistRepository->create([
            'channel_id'  => core()->getCurrentChannel()->id,
            'product_id'  => $id,
            'customer_id' => $customer->id,
        ]);

        return response([
            'data'    => new WishListResource($wishlistItem),
            'message' => __('sarga-api::app.wishlist.success-add'),
        ]);
    }

    /**
     * Move product from wishlist to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCart(Request $request, $id)
    {
        $customer = $request->user();

        $wishlistItem = $this->wishlistRepository->findOneWhere([
            'channel_id'  => core()->getCurrentChannel()->id,
            'product_id'  => $id,
            'customer_id' => $customer->id,
        ]);

        if ($wishlistItem->customer_id != $customer->user()->id) {
            return response([
                'message' => __('rest-api::app.common-response.error.security-warning'),
            ], 400);
        }

        $result = Cart::moveToCart($wishlistItem);

        if ($result) {
            Cart::collectTotals();

            $cart = Cart::getCart();

            return response([
                'data'    => $cart ? new CartResource($cart) : null,
                'message' => __('rest-api::app.wishlist.moved'),
            ]);
        }

        return response([
            'message' => __('rest-api::app.wishlist.option-missing'),
        ], 400);
    }
}