<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Contracts\Notification as NotificationContract;

class Notification extends Model implements  NotificationContract
{
    protected $table = 'marketing_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'last_send_at'
    ];

    protected $dates = ['created_at','updated_at','last_sent_at'];
}