<?php

namespace Sarga\Shop\Repositories;


use Sarga\Shop\Contracts\Notification;
use Webkul\Core\Eloquent\Repository;


class NotificationRepository  extends Repository
{

    public function model()
    {
        return Notification::class;
    }
}