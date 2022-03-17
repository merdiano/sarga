<?php
use Illuminate\Support\Facades\Route;

/**
 * Sales routes.
 */
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        /**
         * Orders routes.
         */
        Route::get('/orders', [\Sarga\Admin\Http\Controllers\Orders::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.orders.index',
        ])->name('admin.sales.orders.index');

        Route::post('/orders/item/{id}/cancel', [\Sarga\Admin\Http\Controllers\Orders::class, 'cancelOrderItem'])
            ->name('admin.sales.orders.cancel_item');
    });
});