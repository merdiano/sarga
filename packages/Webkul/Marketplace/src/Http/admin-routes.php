<?php

Route::group(['middleware' => ['web'],'prefix'=>config('app.admin_url')], function () {

    Route::prefix('marketplace')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //Seller routes
            Route::get('sellers', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@index')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.index'
            ])->name('admin.marketplace.sellers.index');

            Route::get('sellers/create', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@create')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.create'
            ])->name('admin.marketplace.sellers.create');

            Route::get('sellers/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@destroy')
                ->name('admin.marketplace.sellers.delete');

            Route::post('sellers/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.sellers.massdelete');

            Route::post('sellers/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.sellers.massupdate');

            Route::get('sellers/{id}/orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'marketplace::admin.orders.index'
            ])->name('admin.marketplace.sellers.orders.index');

            Route::get('orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'marketplace::admin.orders.index'
            ])->name('admin.marketplace.orders.index');

            Route::post('orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@pay')->defaults('_config', [
                'redirect' => 'admin.marketplace.orders.index'
            ])->name('admin.marketplace.orders.pay');

            Route::get('transactions', 'Webkul\Marketplace\Http\Controllers\Admin\TransactionController@index')->defaults('_config', [
                'view' => 'marketplace::admin.transactions.index'
            ])->name('admin.marketplace.transactions.index');


            //Seller products routes
            Route::get('products', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                'view' => 'marketplace::admin.products.index'
            ])->name('admin.marketplace.products.index');

            Route::post('products/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@destroy')
                ->name('admin.marketplace.products.delete');

            Route::post('products/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.products.index'
            ])->name('admin.marketplace.products.massdelete');

            Route::post('products/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.products.index'
            ])->name('admin.marketplace.products.massupdate');

            Route::get('seller/product/search/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@search')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.products.search'
            ])->name('admin.marketplace.seller.product.search');

            Route::get('seller/product/assign/{seller_id}/{product_id?}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@assignProduct')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.products.assign'
            ])->name('admin.marketplace.seller.product.create');

            Route::post('seller/product/assign/{seller_id}/{product_id?}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@saveAssignProduct')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.seller.product.store');


            Route::get('/products/edit-assign/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@edit')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.products.edit-assign'
            ])->name('admin.marketplace.products.edit-assign');

            Route::put('/products/edit-assign/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@update')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('admin.marketplace.products.assign.update');

            //Seller review routes
            Route::get('reviews', 'Webkul\Marketplace\Http\Controllers\Admin\ReviewController@index')->defaults('_config', [
                'view' => 'marketplace::admin.reviews.index'
            ])->name('admin.marketplace.reviews.index');

            Route::post('reviews/massupdate', 'Webkul\Marketplace\Http\Controllers\Admin\ReviewController@massUpdate')->defaults('_config', [
                'redirect' => 'admin.marketplace.reviews.index'
            ])->name('admin.marketplace.reviews.massupdate');

            //customer edit routes
            Route::put('customers/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\CustomerController@update')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('marketplace.admin.customer.update');

            // seller profile routes start here
            Route::get('sellers/profile/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@editProfile')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.edit'
            ])->name('admin.marketplace.seller.edit');

            Route::put('sellers/profile/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@updateProfile')->defaults('_config', [
                'redirect' => 'admin.marketplace.sellers.index'
            ])->name('marketplace.admin.seller.update');
            // seller profile routes end here

            // Flag routes
            Route::prefix('product-flag')->group(function () {
                Route::get('/', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.productFlagReason.index',
                ])->name('marketplace.admin.product.flag.reason.index');

                Route::get('create', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@create')->defaults('_config', [
                    'view' => 'marketplace::admin.productFlagReason.create',
                ])->name('marketplace.admin.product.flag.reason.create');

                Route::post('create', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@store')->defaults('_config', [
                    'redirect' => 'marketplace.admin.product.flag.reason.index',
                ])->name('marketplace.admin.product.flag.reason.store');

                Route::get('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@edit')->defaults('_config', [
                    'view' => 'marketplace::admin.productFlagReason.edit',
                ])->name('marketplace.admin.product.flag.reason.edit');

                Route::post('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@update')->defaults('_config', [
                    'redirect' => 'marketplace.admin.product.flag.reason.index',
                ])->name('marketplace.admin.product.flag.reason.update');

                Route::get('/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@delete')->defaults('_config', [
                    'redirect' => 'marketplace.admin.product.flag.reason.index',
                ])->name('marketplace.admin.product.flag.reason.delete');

                Route::post('/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\ProductFlagReasonController@massDelete')->defaults('_config', [
                    'redirect' => 'marketplace.admin.product.flag.reason.index',
                ])->name('marketplace.admin.product.flag.reason.mass-delete');
            });

            Route::prefix('seller-flag')->group(function () {
                Route::get('/', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.sellerFlagReason.index',
                ])->name('marketplace.admin.seller.flag.reason.index');

                Route::get('create', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@create')->defaults('_config', [
                    'view' => 'marketplace::admin.sellerFlagReason.create',
                ])->name('marketplace.admin.seller.flag.reason.create');

                Route::post('create', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@store')->defaults('_config', [
                    'redirect' => 'marketplace.admin.seller.flag.reason.index',
                ])->name('marketplace.admin.seller.flag.reason.store');

                Route::get('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@edit')->defaults('_config', [
                    'view' => 'marketplace::admin.sellerFlagReason.edit',
                ])->name('marketplace.admin.seller.flag.reason.edit');

                Route::post('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@update')->defaults('_config', [
                    'redirect' => 'marketplace.admin.seller.flag.reason.index',
                ])->name('marketplace.admin.seller.flag.reason.update');

                Route::get('/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@delete')->defaults('_config', [
                    'redirect' => 'marketplace.admin.seller.flag.reason.index',
                ])->name('marketplace.admin.seller.flag.reason.delete');

                Route::post('/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\SellerFlagReasonController@massDelete')->defaults('_config', [
                    'redirect' => 'marketplace.admin.seller.flag.reason.index',
                ])->name('marketplace.admin.seller.flag.reason.mass-delete');
            });

        });

        // sellers category routes start here
        Route::prefix('seller-categories')->group(function () {
            Route::get('/', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@index')->defaults('_config', [
                'view' => 'marketplace::admin.sellers.category.index'
            ])->name('admin.marketplace.seller.category.index');

            Route::get('create', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@create')->defaults('_config', [
             'view' => 'marketplace::admin.sellers.category.create',
            ])->name('admin.marketplace.seller.category.create');

            Route::post('create', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@store')->defaults('_config', [
                'redirect' => 'admin.marketplace.seller.category.index',
            ])->name('admin.marketplace.seller.category.store');

            Route::get('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@edit')->defaults('_config', [
             'view' => 'marketplace::admin.sellers.category.edit',
            ])->name('admin.marketplace.seller.category.edit');

            Route::post('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@update')->defaults('_config', [
                'redirect' => 'admin.marketplace.seller.category.index',
            ])->name('admin.marketplace.seller.category.update');

            Route::delete('/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@destroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.seller.category.index',
            ])->name('admin.marketplace.seller.category.delete');

            Route::post('/massdelete', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.marketplace.seller.category.index',
            ])->name('admin.marketplace.seller.category.mass-delete');

        });

        // Sellers category routes end here

        // payment request routes start here

        Route::get('payment-request', 'Webkul\Marketplace\Http\Controllers\Admin\PaymentRequestController@index')->defaults('_config', [
            'view' => 'marketplace::admin.paymentRequest.index'
        ])->name('marketplace.admin.payment.request.index');

        // payment request routes end here

    });

});
