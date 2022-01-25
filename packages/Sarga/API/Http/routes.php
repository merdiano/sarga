<?php

use Illuminate\Support\Facades\Route;
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

Route::group(['prefix' => 'api'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);

        //Vendors
        Route::get('vendors',[Vendors::class,'index'])->name('api.vendors');
        Route::get('vendor/products/{vendor_id}',[Vendors::class,'vendor_products'])->name('api.vendor.products');


        //category routes
        Route::get('descendant-categories', [Categories::class, 'index'])->name('api.descendant-categories');
        Route::get('category-brands/{id}', [Categories::class, 'brands']);

        Route::get('categories', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => CategoryRepository::class,
            'resource' => Category::class,
        ])->name('api.categories');
        //attributes by code
        Route::get('attribute-options', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => AttributeOptionRepository::class,
            'resource' => AttributeOption::class,
        ]);

        //Product routes
        Route::get('products', [Products::class, 'index']);

        Route::get('products/{id}', [Products::class, 'get']);
        Route::get('products/{id}/variants', [Products::class, 'variants']);
    });

    Route::group(['prefix' => 'scrap','middleware' =>['scrap']], function ($router){
        Route::put('upload',[IntegrationController::class,'bulk_upload']);
        Route::put('create',[IntegrationController::class,'create']);
    });

    Route::group(['prefix' => 'customer'],function ($router){
        Route::post('register', [Customers::class, 'register']);
        Route::post('login', [Customers::class, 'login']);
        Route::put('profile', [Customers::class, 'update']);
    });


});
