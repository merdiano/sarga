<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Admin\DataGrids\OrderDataGrid;
use Sarga\Shop\Repositories\OrderItemRepository;
use Sarga\Shop\Repositories\OrderRepository;
use Webkul\Admin\Http\Controllers\Sales\OrderController;


class Orders extends OrderController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;


    public function __construct(protected OrderRepository $orderRepository,
                                protected OrderItemRepository $itemRepository)
    {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax())
        {
            return app(OrderDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function cancelOrderItem($item_id)
    {
        $orderItem = $this->orderItemRepository->with('order')
            ->findOrFail($item_id);

        $order = $orderItem->order;

        if($this->orderItemRepository->cancel($orderItem))
        {
            $this->orderRepository->updateOrderStatus($order);
            $this->orderRepository->calculateTotals($order,$orderItem);
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order Item']));
        }
        else
        {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order Item']));
        }

        return redirect()->back();
    }
}