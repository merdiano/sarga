<?php

namespace Sarga\Admin\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Sarga\Admin\Events\OrderChangedEvent;
use Webkul\Notification\Repositories\NotificationRepository;

class Notification implements ShouldQueue
{

    public function orderItem(\Webkul\Sales\Models\Order $order){
//        $this->notificationRepository->create(['type' => 'order', 'order_id' => $order->id]);
        $orderArray = [
            'id'     => $order->id,
            'status' => 'item_cancelled',
        ];
        event(new OrderChangedEvent);
    }
}