<?php

namespace Sarga\Shop\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Sales\Repositories\OrderRepository as WOrderRepository;

class OrderRepository extends WOrderRepository
{
    /**
     * Update order status.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @param  string $orderState
     * @return void
     */
    public function updateOrderStatus($order, $orderState = null)
    {
        Event::dispatch('sales.order.update-status.before', $order);

        if (! empty($orderState)) {
            $status = $orderState;
        } else {
            $status = "pending";

            if ($this->isInCompletedState($order)) {
                $status = 'completed';
            }elseif($this->isInProcessingState($order)){
                $status = 'processing';
            }

            if ($this->isInCanceledState($order)) {
                $status = 'canceled';
            } elseif ($this->isInClosedState($order)) {
                $status = 'closed';
            }
        }

        $order->status = $status;
        $order->save();

        Event::dispatch('sales.order.update-status.after', $order);
    }

    public function isInProcessingState($order){
        return $order->items()->sum('qty_invoiced') > 0;
    }
}