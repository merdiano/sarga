<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Sarga\API\Http\Resources\Customer\OrderResource;
use Sarga\Shop\Repositories\OrderItemRepository;
use Sarga\Shop\Repositories\OrderRepository;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\OrderController;


class Orders extends OrderController
{
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale','currency'];
    public function __construct(protected OrderRepository $orderRepository,
                                protected OrderItemRepository $orderItemRepository)
    {
    }

    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return OrderResource::class;
    }

    public function cancelItem($order_id,$item_id){
        $order = request()->user()->all_orders()->findOrFail($order_id);

        $orderItem = $this->orderItemRepository->with('order')
            ->findOrFail($item_id);

        if($order->shipping_amount>0){
            $order = $this->orderRepository->calculateShipping($order,$orderItem);
        }

        if($this->orderItemRepository->cancel($orderItem))
        {
            $order = $this->orderRepository->updateOrderStatus($order);
            $order = $this->orderRepository->calculateTotals($order);

            Event::dispatch('sales.order.update-status.item', $order);

            return response(['data'=>[
                'order' => new OrderResource($order)],
                'success' => true,
                'message' => trans('admin::app.response.cancel-success', ['name' => 'Order Item'])
            ]);
        }
        else
        {
            return response([
                'success'=>false,
                'message'=>trans('admin::app.response.cancel-error', ['name' => 'Order Item'])
            ]);
        }
    }
}