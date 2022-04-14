<?php
use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Addresses;

/**
 * Customers routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    /**
     * Customer's addresses routes.
     */
    Route::get('customers/{id}/addresses', [Addresses::class, 'index'])->defaults('_config', [
        'view' => 'admin::customers.addresses.index',
    ])->name('admin.customer.addresses.index');

    Route::get('customers/{id}/recipients', [Addresses::class, 'recipients'])->defaults('_config', [
        'view' => 'admin::customers.addresses.recipients',
    ])->name('admin.customer.addresses.recipients');
});