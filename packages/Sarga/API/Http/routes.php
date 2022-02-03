<?php

use Illuminate\Support\Facades\Route;
use Sarga\API\Http\Controllers\Addresses;
use Sarga\API\Http\Controllers\Customers;
use Sarga\API\Http\Controllers\Categories;
use Sarga\API\Http\Controllers\Channels;
use Sarga\API\Http\Controllers\IntegrationController;
use Sarga\API\Http\Controllers\Vendors;
use Sarga\API\Http\Controllers\Products;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\API\Http\Controllers\Shop\ResourceController;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\API\Http\Resources\Catalog\Category;
use Webkul\Core\Repositories\CountryStateRepository;

Route::group(['prefix' => 'api'], function () {
    Route::group(['middleware' => ['locale', 'currency']], function () {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);

        //Vendors
        Route::get('vendors',[Vendors::class,'index'])->name('api.vendors');
        Route::get('vendor/products/{vendor_id}',[Vendors::class,'products'])->name('api.vendor.products');
        Route::get('vendor/brands/{vendor_id}',[Vendors::class,'brands'])->name('api.vendor.brands');

        //category routes
        Route::get('descendant-categories', [Categories::class, 'index'])->name('api.descendant-categories');
        Route::get('category-details/{id}', [Categories::class, 'details']);
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
        Route::get('products/{id}', [Products::class, 'get']);
        Route::get('products/{id}/variants', [Products::class, 'variants']);

        Route::get('states', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CountryStateRepository::class,
            'resource' => Category::class,
        ]);
    });

    //scrap
    Route::group(['prefix' => 'scrap','middleware' =>['scrap']], function (){
        Route::put('upload',[IntegrationController::class,'bulk_upload']);
        Route::put('create',[IntegrationController::class,'create']);
    });

    //customer
    Route::group(['prefix' => 'customer'],function (){
        Route::post('register', [Customers::class, 'register']);
        Route::post('login', [Customers::class, 'login']);
        Route::group(['middleware' => ['auth:sanctum', 'sanctum.customer']], function () {
            Route::put('profile', [Customers::class, 'update']);
            /**
             * Customer address routes.
             */
            Route::get('addresses', [Addresses::class, 'index']);
            Route::post('addresses', [Addresses::class, 'create']);
            Route::get('addresses/{id}', [Addresses::class, 'show']);
            Route::put('addresses/{id}', [Addresses::class, 'update']);
            Route::delete('addresses/{id}', [Addresses::class, 'destroy']);
        });
    });

});
