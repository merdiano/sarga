<?php

use Illuminate\Support\Facades\Route;
use Sarga\Admin\Http\Controllers\Notifications;
use Sarga\Admin\Http\Controllers\PushController;

/**
 * Notification routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    Route::get('delete-notifications', [Notifications::class, 'deleteNotification'])->defaults('_config', [
        'redirect' => 'admin.notification.index',
    ])->name('admin.notification.delete-notification');
    //Notifications
    Route::get('push-notifications', [PushController::class,'index'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.index',
    ])->name('admin.push.index');

    Route::get('push-notifications/create', [PushController::class,'create'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.create',
    ])->name('admin.push.create');

    Route::post('push-notifications/create', [PushController::class,'store'])->defaults('_config', [
        'redirect' => 'admin.push.index',
    ])->name('admin.push.store');

    Route::get('push-notifications/edit/{id}', [PushController::class,'edit'])->defaults('_config', [
        'view' => 'sarga_admin::marketing.notification.view',
    ])->name('admin.push.edit');
});
