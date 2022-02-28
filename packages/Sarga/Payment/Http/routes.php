<?php
use Illuminate\Support\Facades\Route;
use Sarga\Payment\Http\Controllers\AltynAsyrController;
use Sarga\Payment\Http\Controllers\TFEBController;

/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 7/26/2019
 * Time: 16:49
 */
Route::group(['prefix' =>'payment','middleware' => ['web', 'locale', 'theme', 'currency']], function ()
{
    Route::get('altynasyr/redirect', [AltynAsyrController::class,'redirect'])
        ->name('paymentmethod.altynasyr.redirect');

    Route::get('altynasyr/success', [AltynAsyrController::class,'success'])
        ->name('paymentmethod.altynasyr.success');

    Route::get('altynasyr/cancel', [AltynAsyrController::class,'cancel'])
        ->name('paymentmethod.altynasyr.cancel');

    Route::get('tfeb/redirect',[TFEBController::class,'redirect'])
        ->name('paymentmethod.tfeb.redirect');

    Route::get('tfeb/complete', [TFEBController::class,'complete'])
        ->name('paymentmethod.tfeb.complete');

    Route::get('tfeb/cancel', [TFEBController::class,'cancel'])
        ->name('paymentmethod.tfeb.cancel');
});