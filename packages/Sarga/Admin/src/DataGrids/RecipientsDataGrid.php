<?php

namespace Sarga\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;

class RecipientsDataGrid extends DataGrid
{
    use ProvideDataGridPlus;

    /**
     * Index.
     *
     * @var string
     */
    public $index = 'address_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Customer repository instance.
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * Create a new datagrid instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository $customerRepository
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;

        parent::__construct();
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $customer = $this->customerRepository->find(request('id'));

        $queryBuilder = DB::table('addresses as ca')
            ->leftJoin('customers as c', 'ca.customer_id', '=', 'c.id')
            ->addSelect('ca.id as address_id', 'ca.first_name', 'ca.last_name', 'ca.phone')
            ->where('ca.address_type', 'recipient')
            ->where('c.id', $customer->id);

        $this->addFilter('first_name', 'ca.first_name');
        $this->addFilter('last_name', 'ca.last_name');
        $this->addFilter('phone', 'ca.phone');
        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'address_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'first_name',
            'label'      => trans('admin::app.customers.customers.first_name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'last_name',
            'label'      => trans('admin::app.customers.customers.last_name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.customers.customers.phone'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.customer.addresses.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.customer.addresses.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'address']),
            'icon'         => 'icon trash-icon',
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.customers.addresses.delete'),
            'action' => route('admin.customer.addresses.massdelete', request('id')),
            'method' => 'POST',
        ]);
    }
}
