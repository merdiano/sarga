<?php
use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Controllers\ChannelController;
/**
 * Settings routes.
 */
Route::group(['middleware' => ['web', 'admin', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    /**
     * Channels routes.
     */

    Route::get('/channels/create', [ChannelController::class, 'create'])->defaults('_config', [
        'view' => 'sarga_admin::settings.channels.create',
    ])->name('admin.channels.create');


    Route::get('/channels/edit/{id}', [ChannelController::class, 'edit'])->defaults('_config', [
        'view' => 'sarga_admin::settings.channels.edit',
    ])->name('admin.channels.edit');

});
