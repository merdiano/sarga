<?php

namespace Sarga\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;

class AddressDataGrid extends DataGrid
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
//            ->leftJoin('countries', 'ca.country', '=', 'countries.code')
            ->leftJoin('customers as c', 'ca.customer_id', '=', 'c.id')
            ->addSelect('ca.id as address_id', 'ca.company_name', 'ca.address1', 'ca.state', 'ca.city')
            ->where('ca.address_type', CustomerAddress::ADDRESS_TYPE)
            ->where('c.id', $customer->id);

//        $queryBuilder = $queryBuilder->leftJoin('country_states', function ($qb) {
//            $qb->on('ca.state', 'country_states.code')
//                ->on('countries.id', 'country_states.country_id');
//        });

//        $queryBuilder->groupBy('ca.id')
//            ->addSelect(DB::raw(DB::getTablePrefix() . 'country_states.default_name as state_name'));

        $this->addFilter('company_name', 'ca.company_name');
        $this->addFilter('address1', 'ca.address1');
//        $this->addFilter('postcode', 'ca.postcode');
        $this->addFilter('city', 'ca.city');
        $this->addFilter('state_name', DB::raw(DB::getTablePrefix() . 'country_states.default_name'));
//        $this->addFilter('country_name', DB::raw(DB::getTablePrefix() . 'countries.name'));
//        $this->addFilter('default_address', 'ca.default_address');

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
            'index'      => 'company_name',
            'label'      => 'Not',
//            'label'      => trans('admin::app.customers.addresses.company-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'address1',
            'label'      => trans('admin::app.customers.addresses.address-1'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

//        $this->addColumn([
//            'index'      => 'postcode',
//            'label'      => trans('admin::app.customers.addresses.postcode'),
//            'type'       => 'string',
//            'searchable' => true,
//            'sortable'   => true,
//            'filterable' => true,
//        ]);

        $this->addColumn([
            'index'      => 'city',
            'label'      => trans('admin::app.customers.addresses.city'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'state',
            'label'      => trans('admin::app.customers.addresses.state-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

//        $this->addColumn([
//            'index'      => 'country_name',
//            'label'      => trans('admin::app.customers.addresses.country-name'),
//            'type'       => 'string',
//            'searchable' => true,
//            'sortable'   => true,
//            'filterable' => true,
//        ]);

//        $this->addColumn([
//            'index'      => 'default_address',
//            'label'      => trans('admin::app.customers.addresses.default-address'),
//            'type'       => 'boolean',
//            'sortable'   => true,
//            'searchable' => false,
//            'closure'    => function ($row) {
//                if ($row->default_address == 1) {
//                    return '<span class="badge badge-md badge-success"">' . trans('admin::app.customers.addresses.yes') . '</span>';
//                } else {
//                    return trans('admin::app.customers.addresses.dash');
//                }
//            },
//        ]);
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
