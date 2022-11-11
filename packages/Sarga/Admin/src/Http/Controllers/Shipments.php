<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Shop\Repositories\OrderItemRepository;
use Sarga\Shop\Repositories\OrderRepository;
use Webkul\Admin\Http\Controllers\Sales\ShipmentController;
use Webkul\Sales\Repositories\ShipmentRepository;

class Shipments extends ShipmentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\ShipmentRepository   $shipmentRepository
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderitemRepository  $orderItemRepository
     * @return void
     */
    public function __construct(
        ShipmentRepository $shipmentRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository
    )
    {
        $this->_config = request('_config');
        $this->shipmentRepository = $shipmentRepository;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }
    public function isInventoryValidate(&$data){
        return true;
    }
}