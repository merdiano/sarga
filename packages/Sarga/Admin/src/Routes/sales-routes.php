<?php
use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Invoices;

/**
 * Sales routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        /**
         * Orders routes.
         */
        Route::get('/orders', [\Sarga\Admin\Http\Controllers\Orders::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.orders.index',
        ])->name('admin.sales.orders.index');

        Route::get('/orders/view/{id}', [\Sarga\Admin\Http\Controllers\Orders::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.orders.view',
        ])->name('admin.sales.orders.view');

        Route::get('/orders/item/{id}/cancel', [\Sarga\Admin\Http\Controllers\Orders::class, 'cancelOrderItem'])
            ->name('admin.sales.orders.cancel_item');

        Route::post('/shipments/create/{order_id}', [\Sarga\Admin\Http\Controllers\Shipments::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.shipments.store');

        Route::get('/orders/{id}/accept',[\Sarga\Admin\Http\Controllers\Orders::class, 'accept'])->name('admin.sales.orders.accept');
        Route::get('/orders/{id}/ship',[\Sarga\Admin\Http\Controllers\Orders::class, 'ship'])->name('admin.sales.orders.ship');

        /**
         * Invoices routes.
         */
        Route::get('/invoices', [Invoices::class, 'index'])->defaults('_config', [
            'view' => 'admin::sales.invoices.index',
        ])->name('admin.sales.invoices.index');

        Route::get('/invoices/create/{order_id}', [Invoices::class, 'create'])->defaults('_config', [
            'view' => 'admin::sales.invoices.create',
        ])->name('admin.sales.invoices.create');

        Route::post('/invoices/create/{order_id}', [Invoices::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.sales.orders.view',
        ])->name('admin.sales.invoices.store');

        Route::get('/invoices/view/{id}', [Invoices::class, 'view'])->defaults('_config', [
            'view' => 'admin::sales.invoices.view',
        ])->name('admin.sales.invoices.view');

        Route::post('/invoices/send-duplicate/{id}', [Invoices::class, 'sendDuplicateInvoice'])
            ->name('admin.sales.invoices.send-duplicate-invoice');

        Route::get('/invoices/print/{id}', [Invoices::class, 'printInvoice'])->defaults('_config', [
            'view' => 'admin::sales.invoices.print',
        ])->name('admin.sales.invoices.print');

        Route::get('/invoices/{id}/transactions', [Invoices::class, 'invoiceTransactions'])
            ->name('admin.sales.invoices.transactions');
    });
});