<?php

namespace Sarga\Shop\Repositories;

use Webkul\Sales\Repositories\OrderItemRepository as WOrderItemRepo;
class OrderItemRepository extends WOrderItemRepo
{
    public function cancel($item){

        if(! $item->qty_to_cancel) {
            return false;
        }

        $orderItems = [];

        if ($item->getTypeInstance()->isComposite()) {
            foreach ($item->children as $child) {
                $orderItems[] = $child;
            }
        } else {
            $orderItems[] = $item;
        }

        foreach ($orderItems as $orderItem) {
            if ($orderItem->product) {
                $this->returnQtyToProductInventory($orderItem);
            }

            if ($orderItem->qty_ordered) {
                $orderItem->qty_canceled += $orderItem->qty_to_cancel;
                $orderItem->save();

                if ($orderItem->parent && $orderItem->parent->qty_ordered) {
                    $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
                    $orderItem->parent->save();
                }
            } else {
                $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
                $orderItem->parent->save();
            }
        }

        //todo order status check, calculate totals correct
        return true;
    }

}