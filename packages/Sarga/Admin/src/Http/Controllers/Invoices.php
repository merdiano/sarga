<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Shop\Repositories\OrderRepository;
use Webkul\Admin\Http\Controllers\Sales\InvoiceController;
use Webkul\Sales\Repositories\InvoiceRepository;

class Invoices extends InvoiceController
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
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
    }
}