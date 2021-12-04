<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use DB;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

/**
 * Order Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PaymentRequestDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'order_id';

    protected $sortOrder = 'desc'; //asc or desc

    /**
     * SellerRepository object
     *
     * @var Object
     */
    protected $sellerRepository;

    /**
     * Seller object
     *
     * @var Object
     */
    protected $seller;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository $sellerRepository
     * @return void
     */
    public function __construct(SellerRepository $sellerRepository)
    {
        parent::__construct();

        $this->sellerRepository = $sellerRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_orders')
                ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
                ->leftJoin('marketplace_transactions', 'marketplace_orders.id', '=', 'marketplace_transactions.marketplace_order_id')
                ->select('orders.id', 'marketplace_orders.order_id', 'marketplace_orders.base_sub_total', 'marketplace_orders.base_grand_total', 'marketplace_orders.base_commission', 'marketplace_orders.base_seller_total', 'marketplace_orders.base_seller_total_invoiced', 'marketplace_orders.created_at', 'marketplace_orders.status', 'is_withdrawal_requested', 'seller_payout_status', 'marketplace_orders.marketplace_seller_id', 'marketplace_orders.base_discount_amount')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name'), 'orders.increment_id')
                ->addSelect(DB::raw('SUM(marketplace_transactions.base_total) as total_paid'))
                ->groupBy('marketplace_orders.id');

                $this->seller = $this->sellerRepository->findOneWhere(['customer_id' => auth()->guard('customer')->user()->id]);

                $queryBuilder->where('marketplace_orders.marketplace_seller_id', $this->seller->id);

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('base_grand_total', 'marketplace_orders.base_grand_total');
        $this->addFilter('status', 'marketplace_orders.status');
        $this->addFilter('created_at', 'marketplace_orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('marketplace::app.admin.orders.order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('marketplace::app.admin.orders.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('marketplace::app.admin.orders.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'closure' => true,
            'filterable' => true,
            'wrapper' => function ($row) {
                if ($row->status == 'processing')
                    return '<span class="badge badge-md badge-success">' . trans("marketplace::app.admin.orders.processing") . '</span>';
                else if ($row->status == 'completed')
                    return '<span class="badge badge-md badge-success">' . trans("marketplace::app.admin.orders.completed") . '</span>';
                else if ($row->status == "canceled")
                    return '<span class="badge badge-md badge-danger">' . trans("marketplace::app.admin.orders.canceled") . '</span>';
                else if ($row->status == "closed")
                    return '<span class="badge badge-md badge-info">' . trans("marketplace::app.admin.orders.closed") . '</span>';
                else if ($row->status == "pending")
                    return '<span class="badge badge-md badge-warning">' . trans("marketplace::app.admin.orders.pending") . '</span>';
                else if ($row->status == "pending_payment")
                    return '<span class="badge badge-md badge-warning">' . trans("marketplace::app.admin.orders.pending-payment") . '</span>';
                else if ($row->status == "fraud")
                    return '<span class="badge badge-md badge-danger">' . trans("marketplace::app.admin.orders.fraud") . '</span>';
            }
        ]);


        $this->addColumn([
            'index' => 'base_seller_total_invoiced',
            'label' => trans('marketplace::app.admin.orders.seller-total-invoiced'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'total_paid',
            'label' => trans('marketplace::app.admin.orders.total-paid'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'base_remaining_total',
            'label' => trans('marketplace::app.admin.orders.remaining-total'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'wrapper' => function($row) {
                if (! is_null($row->total_paid))
                    return core()->formatBasePrice($row->base_seller_total_invoiced - $row->total_paid);

                return core()->formatBasePrice($row->base_seller_total_invoiced);
            }
        ]);

        $this->addColumn([
            'index' => 'pay',
            'label' => trans('marketplace::app.admin.orders.pay'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => false,
            'closure' => true,
            'wrapper' => function($row) {
                if ($row->seller_payout_status == 'paid') {
                    return trans('marketplace::app.admin.orders.already-paid');
                } else if ($row->seller_payout_status == 'refunded') {
                    return trans('marketplace::app.admin.orders.refunded');
                } else if ($row->seller_payout_status == 'requested') {
                    return trans('marketplace::app.admin.orders.requested');
                }else {
                    $remaining = ! is_null($row->total_paid) ? $row->base_seller_total_invoiced - $row->total_paid : $row->base_seller_total_invoiced;

                    if ((float) $remaining) {
                        return '<a href='. route('marketplace.account.payment.request', $row->id ).' class="btn btn-sm btn-primary" data-id="' . $row->id . '" seller-id="' . $row->marketplace_seller_id .'">' . trans('marketplace::app.shop.sellers.account.sales.payment-request.request-payment') . '</a> ';
                    } else {
                        return trans('marketplace::app.admin.orders.invoice-pending');
                    }
                }
            }

        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'route' => 'admin.sales.orders.view',
            'icon' => 'icon eye-icon',
            'method' => 'GET',
            'title' => 'View'
        ]);
    }
}