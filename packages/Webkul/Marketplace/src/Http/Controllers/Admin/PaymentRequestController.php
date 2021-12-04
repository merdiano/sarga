<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\TransactionRepository;

class PaymentRequestController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerRepository object
     *
     * @var mixed
     */
    protected $sellerRepository;

    /**
     * OrderRepository object
     *
     * @var mixed
     */
    protected $orderRepository;

    /**
     * TransactionRepository object
     *
     * @var mixed
     */
    protected $transactionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository      $sellerRepository
     * @param  Webkul\Marketplace\Repositories\OrderRepository       $orderRepository
     * @param  Webkul\Marketplace\Repositories\TransactionRepository $transactionRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        OrderRepository $orderRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->sellerRepository = $sellerRepository;

        $this->orderRepository = $orderRepository;

        $this->transactionRepository = $transactionRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        

        return view($this->_config['view']);
    }

    /**
     * Update the order for payment and sends mails to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestPayment($orderId)
    {
        $orderRepository = $this->orderRepository->findOneWhere(['order_id' => $orderId]);

        if($orderRepository) {

            $orderRepository->update(['seller_payout_status' => 'requested']);

            session()->flash('success', 'Payment has been requested');
        }

        return redirect()->back();
    }

}
