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

    public function calculateTotals($order, $order_item){
        if($order && $order_item && $order->shipping_amount >0){
            $total_weight = $order_item->total_weight;
            foreach($order->items as $item ){
                if($item->id !== $order_item->id)
                    $total_weight += ($item->qty_ordered - $item->qty_canceled) * $item->weight;
            }
            $shipping_price = $order->shipping_amount/$total_weight;
            $base_shipping_price = $order->base_shipping_amount/$total_weight;
            $canceled_amount = $shipping_price * $order_item->qty_canceled * $order_item->weight;
            $canceled_base_amount = $base_shipping_price * $order_item->qty_canceled * $order_item->weight;
            $order->shipping_amount = $order->shipping_amount - $canceled_amount;
            $order->base_shipping_amount = $order->base_shipping_amount - $canceled_base_amount;
            $order->grand_total = $order->grand_total - $canceled_amount;
            $order->base_grand_total = $order->base_grand_total - $canceled_base_amount;
        }

        if($order && $order_item){
            $total_price = $order_item->price * $order_item->qty_canceled;
            $total_base_price = $order_item->base_price * $order_item->qty_canceled;
            $total_discount = $total_price * $order_item->discount_percent/100;
            $total_base_discount = $total_base_price * $order_item->discount_percent/100;
            $order->sub_total = $order->sub_total - $total_price;
            $order->base_sub_total = $order->base_sub_total - $total_base_price;
            $order->grand_total = $order->grand_total - ($total_price + $total_discount);
            $order->base_grand_total = $order->base_grand_total-($total_base_price + $total_base_discount);
        }

        $order->save();

        return $order;
    }
}