<?php

use Sarga\API\Http\Controllers\Categories;
use Sarga\API\Http\Controllers\Channels;
use Sarga\API\Http\Controllers\Resources;
use Webkul\Attribute\Repositories\AttributeRepository;
use Sarga\API\Http\Resources\Catalog\Attribute;

Route::group(['prefix' => 'api'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);

        //category routes
        Route::get('descendant-categories', [Categories::class, 'index']);
        Route::get('category-brands/{id}', [Categories::class, 'brands']);

        //attributes by code
        Route::get('attribute-by-code/{code}', [Resources::class, 'get'])->defaults('_config', [
            'repository' => AttributeRepository::class,
            'resource' => Attribute::class,
        ]);

    });
});
