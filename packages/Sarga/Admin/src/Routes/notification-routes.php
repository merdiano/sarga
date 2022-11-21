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
    //Notifications
    Route::get('push-notifications', [Notifications::class,'index'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.index',
    ])->name('admin.push.index');

    Route::get('push-notifications/create', [Notifications::class,'create'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.create',
    ])->name('admin.push.create');

    Route::post('push-notifications/create', [Notifications::class,'store'])->defaults('_config', [
        'redirect' => 'sarga_admin.notifications.index',
    ])->name('admin.push.store');

    Route::get('push-notifications/edit/{id}', [Notifications::class,'edit'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.view',
    ])->name('admin.push.edit');
});
