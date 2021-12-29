<?php

use Illuminate\Support\Facades\Route;
use Webkul\Attribute\Http\Controllers\AttributeController;
use Webkul\Attribute\Http\Controllers\AttributeFamilyController;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController;
use Webkul\Product\Http\Controllers\ProductController;

/**
 * marketplace routes.
 */
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('marketplace/seller-categories')->group(function () {

        Route::get('/', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@index')->defaults('_config', [
            'view' => 'sarga_admin::sellers.category.index'
        ])->name('admin.marketplace.seller.category.index');

        Route::get('create', [SellerCategoryController::class, 'create'])->defaults('_config', [
            'view' => 'sarga_admin::sellers.category.create',
        ])->name('admin.marketplace.seller.category.create');

        Route::get('edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerCategoryController@edit')->defaults('_config', [
            'view' => 'sarga_admin::sellers.category.edit',
        ])->name('admin.marketplace.seller.category.edit');

        // seller profile routes start here
        Route::get('sellers/profile/edit/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@editProfile')->defaults('_config', [
            'view' => 'marketplace::admin.sellers.edit'
        ])->name('admin.marketplace.seller.edit');

        // seller profile routes end here
    });
});
