<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Admin\DataGrids\OrderDataGrid;
use Sarga\Shop\Repositories\OrderItemRepository;
use Webkul\Admin\Http\Controllers\Sales\OrderController;

class Orders extends OrderController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(OrderDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function cancelOrderItem(OrderItemRepository $repository,$item_id){

    }
}