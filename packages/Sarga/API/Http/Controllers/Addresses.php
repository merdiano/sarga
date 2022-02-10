<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Sarga\API\Http\Requests\AddressRequest;
use Sarga\API\Http\Requests\RecipientRequest;
use Sarga\API\Http\Resources\Customer\AddressResource;
use Sarga\API\Http\Resources\Customer\RecipientResource;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\AddressController;


class Addresses extends AddressController
{
    /**
     * Get customer addresses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $addresses = $request->user()
            ->addresses()
            ->where('address_type','customer')
            ->get();

        return response([
            'data' => AddressResource::collection($addresses),
        ]);
    }

    public function recipients(Request $request){

        $addresses = DB::table('addresses')
            ->where('customer_id',$request->user()->id)
            ->where('address_type','recipient')
            ->get();

        return response([
            'data' => RecipientResource::collection($addresses),
        ]);
    }

    public function createAddress(AddressRequest $request)
    {
        $data = $request->all();
        $data['address1'] = implode(PHP_EOL, array_filter($data['address1']));
        $data['customer_id'] = $request->user()->id;
//        $data['country'] = 'Turkmenistan';
//        $data['postcode'] = '0000';
        $data['first_name'] = $request->user()->first_name;
        $data['last_name'] = $request->user()->last_name;
        $data['company_name'] = $request->get('note');
        $data['address_type'] = 'customer';

        $customerAddress = $this->customerAddressRepository->create($data);

        return response([
            'data'    => new AddressResource($customerAddress),
            'message' => 'Your address has been created successfully.',
        ]);
    }

    public function createRecipient(RecipientRequest $request){
        $data = $request->all();
        $data['address_type'] = 'recipient';
        $data['customer_id'] = $request->user()->id;
        $data['address1'] = 'recipient';
        $data['city'] = 'recipient';

        return response([
            'data'    => new RecipientResource($this->customerAddressRepository->create($data)),
            'message' => 'Your recipient has been created successfully.',
        ]);
    }

    public function updateAddress(AddressRequest $request, int $id)
    {
        $data = $request->all();
        $data['address1'] = implode(PHP_EOL, array_filter($data['address1']));
        $data['company_name'] = $request->get('note');
        $customerAddress = $this->customerAddressRepository->update($data, $id);

        return response([
            'data'    => new AddressResource($customerAddress),
            'message' => 'Your address has been updated successfully.',
        ]);
    }

    public function updateRecipient(RecipientRequest $request, int $id)
    {
        if(\DB::table('addresses')->where('id',$id)->update($request->all())){
            return response([
                'message' => 'Your recipient has been updated successfully.',
            ]);
        }

        return response([
            'error' => 'not found'
        ]);

    }
}