<?php

use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Notifications;
/**
 * Notification routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    Route::get('delete-notifications', [Notifications::class, 'deleteNotification'])->defaults('_config', [
        'redirect' => 'admin.notification.index',
    ])->name('admin.notification.delete-notification');

});
