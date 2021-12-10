<?php

use Illuminate\Support\Facades\Route;
use Sarga\API\Http\Controllers\Categories;
use Sarga\API\Http\Controllers\Channels;
use Sarga\API\Http\Controllers\IntegrationController;
use Sarga\API\Http\Controllers\Vendors;
use Sarga\API\Http\Controllers\Products;
use Webkul\API\Http\Controllers\Shop\ResourceController;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Sarga\API\Http\Resources\Catalog\AttributeOption;

Route::group(['prefix' => 'api'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);

        //Vendors
        Route::get('vendors',[Vendors::class,'index']);

        //category routes
        Route::get('descendant-categories', [Categories::class, 'index']);
        Route::get('category-brands/{id}', [Categories::class, 'brands']);

        //attributes by code
        Route::get('attribute-options', [ResourceController::class, 'index'])->defaults('_config', [
            'repository' => AttributeOptionRepository::class,
            'resource' => AttributeOption::class,
        ]);

        //Product routes
        Route::get('products', [Products::class, 'index']);

        Route::get('products/{id}', [Products::class, 'get']);
    });

    Route::group(['prefix' => 'scrap','middleware' =>['scrap']], function ($router){
        Route::put('upload',[IntegrationController::class,'bulk_upload']);
    });
});
