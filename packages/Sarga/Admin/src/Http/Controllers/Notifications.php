<?php

namespace Sarga\Admin\Http\Controllers;

use Webkul\Notification\Http\Controllers\Admin\NotificationController;

class Notifications extends NotificationController
{
    public function deleteNotification(){

        if($id = request()->get('id')){
            $this->notificationRepository->delete($id);
        }
        return redirect()->route($this->_config['redirect']);
    }

}