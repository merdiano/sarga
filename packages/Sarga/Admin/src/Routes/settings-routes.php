<?php
use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Scrap;
use Webkul\Core\Http\Controllers\ChannelController;
/**
 * Settings routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    /*
     * Scrap
     */
    Route::get('/scrap/categories', [Scrap::class, 'index'])->defaults('_config', [
        'view' => 'sarga_admin::scrap.category.index',
    ])->name('admin.scrap-categories.index');

    Route::get('/scrap/products', [Scrap::class, 'index'])->defaults('_config', [
        'view' => 'sarga_admin::scrap.product.index',
    ])->name('admin.scrap-products.index');

});
