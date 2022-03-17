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

        Route::post('/shipments/create/{order_id}', [\Sarga\Admin\Http\Controllers\Shipments::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.shipments.store');
    });
});