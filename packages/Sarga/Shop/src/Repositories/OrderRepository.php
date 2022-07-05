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
        return $order;
    }

    public function isInProcessingState($order){
        return $order->items()->sum('qty_invoiced') > 0;
    }

    public function calculateTotals($order){

        $order->sub_total = $order->items()->sum('total');
        $order->base_sub_total = $order->items()->sum('base_total');

        $order->discount_amount = $order->items()->sum('discount_amount');
        $order->base_discount_amount = $order->items()->sum('discount_amount');

        $order->grand_total = $order->shipping_amount + $order->sub_total - $order->discount - $order->shipping_discount_amount;
        $order->base_grand_total = $order->base_shipping_amount + $order->base_sub_total -  $order->base_discount_amount - $order->base_shipping_discount_amount;

        $order->save();

        return $order;
    }

    public function calculateShipping($order,$order_item){
        $total_weight = $order->items->sum('total_weight');
//        $order_item->total_weight = ($order_item->qty_ordered - $order_item->qty_canceled) * $order_item->weight;

        $shipping_price = $order->shipping_amount/$total_weight;
        $base_shipping_price = $order->base_shipping_amount/$total_weight;

        $canceled_amount = $shipping_price * $order_item->qty_to_cancel * $order_item->weight;
        $canceled_base_amount = $base_shipping_price * $order_item->qty_to_cancel * $order_item->weight;

        $order->shipping_amount = $order->shipping_amount - $canceled_amount;
        $order->base_shipping_amount = $order->base_shipping_amount - $canceled_base_amount;

        return $order;
    }
}