<?php
use Sarga\API\Http\Controllers\Channels;

Route::group(['prefix' => 'api'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Channel routes
        Route::get('channels',[Channels::class, 'index']);
        Route::get('channels/{channel_id}',[Channels::class, 'get']);
    });
});
