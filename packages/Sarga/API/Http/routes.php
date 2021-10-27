<?php

use Sarga\API\Http\Controllers\Categories;
use Sarga\API\Http\Controllers\Channels;

Route::group(['prefix' => 'api'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);

        //category routes
        Route::get('descendant-categories', [Categories::class, 'index']);
        Route::get('category-brands/{id}', [Categories::class, 'brands']);

    });
});
