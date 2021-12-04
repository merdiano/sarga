<?php
use Illuminate\Support\Facades\Route;
use Sarga\Scrap\Http\Controllers\LCW;
use Sarga\Scrap\Http\Controllers\Trendyol;

Route::group(['prefix' => 'scrapi'], function ($router) {
    Route::group(['middleware' => ['locale', 'currency']], function ($router) {
        //Trendyol routes
        Route::get('trendyol',[Trendyol::class, 'index']);

        //LCW
        Route::get('lcw',[LCW::class, 'index']);
    });

});
