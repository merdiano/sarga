<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Requests\AddressRequest;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\AddressController;
use Webkul\RestApi\Http\Resources\V1\Shop\Customer\CustomerAddressResource;

class Addresses extends AddressController
{
    /**
     * Store address.
     *
     * @param  \Webkul\Customer\Http\Requests\CustomerAddressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(AddressRequest $request)
    {
        $data = $request->all();
        $data['address1'] = implode(PHP_EOL, array_filter($data['address1']));
        $data['customer_id'] = $request->user()->id;
        $data['country'] = 'Turkmenistan';
        $data['postcode'] = '0000';

        $customerAddress = $this->customerAddressRepository->create($data);

        return response([
            'data'    => new CustomerAddressResource($customerAddress),
            'message' => 'Your address has been created successfully.',
        ]);
    }
}