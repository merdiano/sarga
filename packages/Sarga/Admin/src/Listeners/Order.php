<?php

namespace Sarga\Admin\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sarga\Admin\Http\Resources\OrderResource;
use Webkul\Sales\Models\Order as OrderModel;

class Order implements ShouldQueue
{
    public $delay = 60;
    public $queue = 'redis';

    public function newOrder(OrderModel $order){
        try {
            Http::post(env('HTTP_MANAGER_ADDRESS','https://panel.sargagroup.cf/api').'/orders',
                OrderResource::make($order));
        }catch(Exception $ex){
            Log::error($ex);
        }
    }
}