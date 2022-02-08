<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Admin\DataGrids\AddressDataGrid;
use Sarga\Admin\DataGrids\RecipientsDataGrid;

class Addresses extends \Webkul\Admin\Http\Controllers\Customer\AddressController
{
    /**
     * Fetch address by customer id.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $customer = $this->customerRepository->find($id);

        if (request()->ajax()) {
            return app(AddressDataGrid::class)->toJson();
        }

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Fetch address by customer id.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function recipients($id)
    {
        $customer = $this->customerRepository->find($id);

        if (request()->ajax()) {
            return app(RecipientsDataGrid::class)->toJson();
        }

        return view($this->_config['view'], compact('customer'));
    }
}