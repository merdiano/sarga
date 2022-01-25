<?php
use Illuminate\Support\Facades\Route;
use Sarga\Brand\Http\Controllers\BrandController;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('catalog')->group(function () {
        /**
         * Categories routes.
         */
        Route::get('/brands', [BrandController::class, 'index'])->defaults('_config', [
            'view' => 'admin::catalog.categories.index',
        ])->name('admin.catalog.categories.index');

        Route::get('/brands/create', [BrandController::class, 'create'])->defaults('_config', [
            'view' => 'admin::catalog.categories.create',
        ])->name('admin.catalog.categories.create');

        Route::post('/brands/create', [BrandController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.store');

        Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.categories.edit',
        ])->name('admin.catalog.categories.edit');

        Route::put('/brands/edit/{id}', [BrandController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.update');

        Route::post('/brands/delete/{id}', [BrandController::class, 'destroy'])->name('admin.catalog.categories.delete');

        Route::post('brands/massdelete', [BrandController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.massdelete');

        Route::post('/brands/product/count', [BrandController::class, 'productCount'])->name('admin.catalog.categories.product.count');
    });
});

