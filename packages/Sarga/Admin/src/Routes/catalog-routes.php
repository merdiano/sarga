<?php

use Illuminate\Support\Facades\Route;
use Webkul\Attribute\Http\Controllers\AttributeController;
use Webkul\Attribute\Http\Controllers\AttributeFamilyController;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\Product\Http\Controllers\ProductController;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('catalog')->group(function () {
        /**
         * Categories routes.
         */
        Route::get('/categories/create', [CategoryController::class, 'create'])->defaults('_config', [
            'view' => 'sarga_admin::catalog.categories.create',
        ])->name('admin.catalog.categories.create');

        Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->defaults('_config', [
            'view' => 'sarga_admin::catalog.categories.edit',
        ])->name('admin.catalog.categories.edit');

    });
});
