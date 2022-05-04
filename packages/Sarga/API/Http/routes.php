<?php

use Illuminate\Support\Facades\Route;
use Sarga\API\Http\Controllers\Addresses;
use Sarga\API\Http\Controllers\Carts;
use Sarga\API\Http\Controllers\Checkout;
use Sarga\API\Http\Controllers\Customers;
use Sarga\API\Http\Controllers\Categories;
use Sarga\API\Http\Controllers\Channels;
use Sarga\API\Http\Controllers\IntegrationController;
use Sarga\API\Http\Controllers\Orders;
use Sarga\API\Http\Controllers\Vendors;
use Sarga\API\Http\Controllers\Products;
use Sarga\API\Http\Controllers\Wishlists;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\API\Http\Controllers\Shop\ResourceController;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\API\Http\Resources\Catalog\Category;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\InvoiceController;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\ShipmentController;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\TransactionController;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\WishlistController;

Route::group(['prefix' => 'api'], function () {
    Route::group(['middleware' => ['locale', 'currency']], function () {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);
        Route::get('sliders',[\Sarga\API\Http\Controllers\Banners::class,'allResources']);

        //Vendors
        Route::get('vendors',[Vendors::class,'index'])->name('api.vendors');
        Route::get('sources',[Vendors::class,'sources'])->name('api.sources');
        Route::get('vendor/products/{vendor_id}',[Vendors::class,'products'])->name('api.vendor.products');
        Route::get('vendor/brands/{vendor_id}',[Vendors::class,'brands'])->name('api.vendor.brands');

        //category routes
        Route::get('descendant-categories', [Categories::class, 'descendantCategories'])->name('api.descendant-categories');
        Route::get('categories', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CategoryRepository::class,
            'resource' => Category::class,
        ])->name('api.categories');
        Route::get('categories/{id}/filters',[Categories::class,'filters']);

        //attributes by code
        Route::get('attribute-options', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => AttributeOptionRepository::class,
            'resource' => AttributeOption::class,
        ]);

        //Product routes
        Route::get('products', [Products::class, 'index']);
        Route::get('products-discounted', [Products::class, 'discountedProducts']);
        Route::get('products-popular', [Products::class, 'popularProducts']);
        Route::get('products-search', [Products::class, 'searchProducts']);
        Route::get('suggestions', [\Sarga\API\Http\Controllers\SearchController::class, 'index']);
        Route::get('products/{id}', [Products::class, 'get']);
        Route::get('products/{id}/variants', [Products::class, 'variants']);

        Route::get('states', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CountryStateRepository::class,
            'resource' => Category::class,
        ]);
        //customer
        Route::group(['prefix' => 'customer'],function (){
            Route::post('register', [Customers::class, 'register']);
            Route::post('login', [Customers::class, 'login']);
            Route::group(['middleware' => ['auth:sanctum', 'sanctum.customer']], function () {
                Route::get('profile',[Customers::class, 'get']);
                Route::put('profile', [Customers::class, 'update']);
                /**
                 * Customer address routes.
                 */
                Route::get('addresses', [Addresses::class, 'index']);
                Route::post('addresses', [Addresses::class, 'createAddress']);
                Route::put('addresses/{id}', [Addresses::class, 'updateAddress']);
                Route::delete('addresses/{id}', [Addresses::class, 'destroy']);
                /**
                 * Customer wishlist routes.
                 */
                Route::get('wishlist', [Wishlists::class, 'index']);
                Route::post('wishlist/{id}', [Wishlists::class, 'addOrRemove']);
                Route::post('wishlist/{id}/move-to-cart', [Wishlists::class, 'moveToCart']);
                /**
                 * Recipients
                 */
                Route::get('recipients', [Addresses::class, 'recipients']);
                Route::post('recipients', [Addresses::class, 'createRecipient']);
                Route::put('recipients/{id}', [Addresses::class, 'updateRecipient']);
                Route::delete('recipients/{id}', [Addresses::class, 'destroy']);

                /**
                 * Customer cart routes.
                 */
                Route::get('cart', [Carts::class, 'get']);
                Route::post('cart/add/{productId}', [Carts::class, 'add']);
                Route::put('cart/update', [Carts::class, 'update']);
                Route::delete('cart/remove/{cartItemId}', [Carts::class, 'removeItem']);
                Route::delete('cart/empty', [Carts::class, 'empty']);
                Route::post('cart/move-to-wishlist/{cartItemId}', [Carts::class, 'moveToWishlist']);
                Route::post('cart/coupon', [Carts::class, 'applyCoupon']);
                Route::delete('cart/coupon', [Carts::class, 'removeCoupon']);

                /**
                 * Customer checkout routes.
                 */
                Route::get('checkout', [Checkout::class, 'index']);
                Route::post('checkout/save-shipping', [Checkout::class, 'saveShipping']);
                Route::post('checkout/save-payment', [Checkout::class, 'savePayment']);
                Route::post('checkout/check-minimum-order', [Checkout::class, 'checkMinimumOrder']);
                Route::post('checkout/save-order', [Checkout::class, 'saveOrder']);

                /**
                 * Customer sale routes.
                 */
                Route::get('orders', [Orders::class, 'allResources']);
                Route::get('orders/{id}', [Orders::class, 'getResource']);
                Route::post('orders/{id}/cancel', [Orders::class, 'cancel']);
                Route::post('orders/{id}/cancel/{item_id}', [Orders::class, 'cancelItem']);
                Route::get('invoices', [InvoiceController::class, 'allResources']);
                Route::get('invoices/{id}', [InvoiceController::class, 'getResource']);
                Route::get('shipments', [ShipmentController::class, 'allResources']);
                Route::get('shipments/{id}', [ShipmentController::class, 'getResource']);
                Route::get('transactions', [TransactionController::class, 'allResources']);
                Route::get('transactions/{id}', [TransactionController::class, 'getResource']);
            });
        });
    });

    //scrap
    Route::group(['prefix' => 'scrap','middleware' =>['scrap']], function (){
        Route::put('upload',[IntegrationController::class,'bulk_upload']);
        Route::put('create',[IntegrationController::class,'create']);
        Route::put('update',[IntegrationController::class,'update']);
    });

});
