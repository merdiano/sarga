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
                $orderItem->total_weight -= $orderItem->weight * $orderItem->qty_to_cancel;
                $prosent_discount = ($orderItem->total* $orderItem->discount_percent)/100;
                $base_prosent_discount = ($orderItem->base_total* $orderItem->discount_percent)/100;
                $prosentsizDiscount = $orderItem->discount_amount - $prosent_discount;
                $base_prosentsizDiscount = $orderItem->base_discount_amount - $base_prosent_discount;
                $shtukDiscount = $prosentsizDiscount/($orderItem->qty_ordered-$orderItem->qty_canceled);
                $base_shtukDiscount = $base_prosentsizDiscount/($orderItem->qty_ordered-$orderItem->qty_canceled);
                $orderItem->qty_canceled += $orderItem->qty_to_cancel;
                $orderItem->total = $orderItem->price * ($orderItem->qty_ordered -$orderItem->qty_canceled);
                $orderItem->discount_amount = ($orderItem->total* $orderItem->discount_percent)/100 + ($shtukDiscount*($orderItem->qty_ordered-$orderItem->qty_canceled));
                $orderItem->base_total = $orderItem->base_price * ($orderItem->qty_ordered -$orderItem->qty_canceled);
                $orderItem->base_discount_amount = ($orderItem->base_total* $orderItem->discount_percent)/100 + ($base_shtukDiscount*($orderItem->qty_ordered-$orderItem->qty_canceled));

                $orderItem->save();

                if ($orderItem->parent && $orderItem->parent->qty_ordered) {
                    $orderItem->parent->total_weight -= $orderItem->parent->weight * $orderItem->parent->qty_to_cancel;
                    $prosent_discount = ($orderItem->parent->total* $orderItem->parent->discount_percent)/100;
                    $base_prosent_discount = ($orderItem->parent->base_total* $orderItem->parent->discount_percent)/100;
                    $prosentsizDiscount = $orderItem->parent->discount_amount - $prosent_discount;
                    $base_prosentsizDiscount = $orderItem->parent->base_discount_amount - $base_prosent_discount;
                    $shtukDiscount = $prosentsizDiscount/($orderItem->parent->qty_ordered-$orderItem->parent->qty_canceled);
                    $base_shtukDiscount = $base_prosentsizDiscount/($orderItem->parent->qty_ordered-$orderItem->parent->qty_canceled);
                    $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
                    $orderItem->parent->total = $orderItem->parent->price * ($orderItem->parent->qty_ordered -$orderItem->parent->qty_canceled);
                    $orderItem->parent->discount_amount = ($orderItem->parent->total* $orderItem->parent->discount_percent)/100 +
                        ($shtukDiscount*($orderItem->parent->qty_ordered - $orderItem->parent->qty_canceled));
                    $orderItem->parent->base_total = $orderItem->parent->base_price * ($orderItem->parent->qty_ordered -$orderItem->parent->qty_canceled);
                    $orderItem->parent->base_discount_amount = ($orderItem->parent->base_total * $orderItem->parent->discount_percent)/100 +
                        ($base_shtukDiscount * ($orderItem->parent->qty_ordered - $orderItem->parent->qty_canceled));
                    $orderItem->parent->save();
                }
            } else {
                $orderItem->parent->total_weight -= $orderItem->parent->weight * $orderItem->parent->qty_to_cancel;
                $prosent_discount = ($orderItem->parent->total* $orderItem->parent->discount_percent)/100;
                $base_prosent_discount = ($orderItem->parent->base_total* $orderItem->parent->discount_percent)/100;
                $prosentsizDiscount = $orderItem->parent->discount_amount - $prosent_discount;
                $base_prosentsizDiscount = $orderItem->parent->base_discount_amount - $base_prosent_discount;
                $shtukDiscount = $prosentsizDiscount/($orderItem->parent->qty_ordered-$orderItem->parent->qty_canceled);
                $base_shtukDiscount = $base_prosentsizDiscount/($orderItem->parent->qty_ordered-$orderItem->parent->qty_canceled);
                $orderItem->parent->qty_canceled += $orderItem->parent->qty_to_cancel;
                $orderItem->parent->total = $orderItem->parent->price * ($orderItem->parent->qty_ordered -$orderItem->parent->qty_canceled);
                $orderItem->parent->discount_amount = ($orderItem->parent->total* $orderItem->parent->discount_percent)/100 +
                    ($shtukDiscount*($orderItem->parent->qty_ordered - $orderItem->parent->qty_canceled));
                $orderItem->parent->base_total = $orderItem->parent->base_price * ($orderItem->parent->qty_ordered -$orderItem->parent->qty_canceled);
                $orderItem->parent->base_discount_amount = ($orderItem->parent->base_total * $orderItem->parent->discount_percent)/100 +
                    ($base_shtukDiscount * ($orderItem->parent->qty_ordered - $orderItem->parent->qty_canceled));
                $orderItem->parent->save();
            }
        }

        //todo order status check, calculate totals correct
        return true;
    }

}