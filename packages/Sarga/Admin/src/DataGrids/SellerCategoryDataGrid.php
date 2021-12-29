<?php

namespace Sarga\Admin\DataGrids;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Seller Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerCategoryDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'id';

    protected $sortOrder = 'desc'; //asc or desc

    protected $enableFilterMap = true;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('seller_categories')
                            ->leftJoin('marketplace_sellers', 'seller_categories.seller_id', 'marketplace_sellers.id')
                            ->leftJoin('customers', 'marketplace_sellers.customer_id', 'customers.id')

                ->select(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as name'),
                         'seller_categories.categories',
                         'seller_categories.id',
                         'seller_categories.type'
                );

                $this->addFilter('customer_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));


        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('marketplace::app.admin.sellers.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('marketplace::app.admin.flag.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => 'Type',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'edit',
            'method' => 'GET',
            'route' => 'admin.marketplace.seller.category.edit',
            'icon' => 'icon pencil-lg-icon',
            'title' => ''
        ], true);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'delete',
            'route' => 'admin.marketplace.seller.category.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'icon trash-icon',
            'title' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product'])
        ], true);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('marketplace::app.admin.sellers.delete'),
            'action' => route('admin.marketplace.sellers.massdelete'),
            'method' => 'POST',
            'title'  => ''
        ], true) ;
    }
}