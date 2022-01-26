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
            'view' => 'brand::admin.index',
        ])->name('admin.catalog.brand.index');

        Route::get('/brands/create', [BrandController::class, 'create'])->defaults('_config', [
            'view' => 'brand::admin.create',
        ])->name('admin.catalog.brand.create');

        Route::post('/brands/create', [BrandController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.brand.index',
        ])->name('admin.catalog.brand.store');

        Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->defaults('_config', [
            'view' => 'brand::admin.edit',
        ])->name('admin.catalog.brand.edit');

        Route::post('/brands/edit/{id}', [BrandController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.brand.index',
        ])->name('admin.catalog.brand.update');

        Route::post('/brands/delete/{id}', [BrandController::class, 'destroy'])
            ->name('admin.catalog.brand.delete');

        Route::post('brands/massdelete', [BrandController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.catalog.brand.index',
        ])->name('admin.catalog.brand.massdelete');

        Route::post('/brands/product/count', [BrandController::class, 'productCount'])
            ->name('admin.catalog.brand.product.count');
    });
});

