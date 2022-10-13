<?php
use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Menus;
use Sarga\Admin\Http\Controllers\Scrap;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('catalog')->group(function () {
        /**
         * Menu routes.
         */
        Route::get('menus', [Menus::class, 'index'])->defaults('_config', [
            'view' => 'sarga_admin::catalog.menus.index',
        ])->name('admin.catalog.menus.index');

        Route::get('menus/create', [Menus::class, 'create'])->defaults('_config', [
            'view' => 'sarga_admin::catalog.menus.create',
        ])->name('admin.catalog.menus.create');

        Route::post('menus/create', [Menus::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.menus.index',
        ])->name('admin.catalog.menus.store');

        Route::get('menus/edit/{id}', [Menus::class, 'edit'])->defaults('_config', [
            'view' => 'sarga_admin::catalog.menus.edit',
        ])->name('admin.catalog.menus.edit');

        Route::put('menus/edit/{id}', [Menus::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.menus.index',
        ])->name('admin.catalog.menus.update');

        Route::post('menus/delete/{id}', [Menus::class, 'destroy'])->name('admin.catalog.menus.delete');

        Route::post('menus/massdelete', [Menus::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.catalog.menus.index',
        ])->name('admin.catalog.menus.massdelete');

        Route::get('menus/brands',[Menus::class, 'brands'])->name('admin.catalog.menus.brandsearch');

        /*
        * Scrap
        */
        Route::get('scrap/trendyol', [Scrap::class, 'index'])->defaults('_config', [
            'view' => 'sarga_admin::scrap.trendyol.index',
        ])->name('admin.scrap-trendyol.index');

        Route::get('scrap/trendyol/scrap', [Scrap::class, 'trendyolScarp'])->name('admin.catalog.scrap.trendyol.scrap');
        Route::get('scrap/trendyol/import', [Scrap::class, 'trendyolImport'])->name('admin.catalog.scrap.trendyol.import');


//        Route::get('/scrap/products', [Scrap::class, 'index'])->defaults('_config', [
//            'view' => 'sarga_admin::scrap.product.index',
//        ])->name('admin.scrap-products.index');
    });


});