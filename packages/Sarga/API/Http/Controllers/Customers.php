<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Sarga\API\Http\Resources\Customer\CustomerResource;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\AuthController;

class Customers extends AuthController
{
    /**
     * Get details for current logged in customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $customer = $request->user();

        return response([
            'data' => new CustomerResource($customer),
        ]);
    }
    /**
     * Controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        CustomerGroupRepository $customerGroupRepository,
        CustomerAddressRepository $addressRepository
    ) {
        parent::__construct($customerRepository, $customerGroupRepository);

        $this->addressRepository = $addressRepository;

    }
    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'phone'      => 'required|digits:8|unique:customers,phone',
            'password'   => 'required|min:6',
            'gender'     => 'in:Male,Female',
            'device_name'=> 'required'
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        $customer = $this->customerRepository->create([
            'first_name'  => $request->get('first_name'),
            'last_name'   => $request->get('last_name'),
            'phone'       => $request->get('phone'),
            'password'    => bcrypt($request->get('password')),
            'channel_id'  => core()->getCurrentChannel()->id,
            'is_verified' => 1,
            'gender'      => $request->get('gender'),
            'customer_group_id' => $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id
        ]);

        $this->addressRepository->create([
            'address_type' => 'recipient',
            'address1' => 'recipient',
            'city' => 'recipient',
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone' => $request->get('phone'),
            'customer_id' => $customer->id
        ]);

        return response([
            'data'    => new \Webkul\RestApi\Http\Resources\V1\Shop\Customer\CustomerResource($customer),
            'message' => 'Registered in successfully.',
            'token'   => $customer->createToken($request->device_name, ['role:customer'])->plainTextToken,
        ]);
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'phone'      => 'required|digits:8',
            'password'   => 'required|min:6',
            'device_name' => 'required',
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        $customer = $this->customerRepository->where('phone', $request->phone)->first();

        if (! $customer || ! Hash::check($request->password, $customer->password)) {
            return response()->json([
                'error' => 'The provided credentials are incorrect.',
            ], 401);
        }

        Event::dispatch('customer.after.login', $request->get('phone'));

        /**
         * Preventing multiple token creation.
         */
        $customer->tokens()->delete();

        return response([
            'data'    => CustomerResource::make($customer),
            'message' => 'Logged in successfully.',
            'token'   => $customer->createToken($request->device_name, ['role:customer'])->plainTextToken,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $customer = $request->user();

        $validation = Validator::make($request->all(), [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'gender'        => 'in:Male,Female',
            'date_of_birth' => 'nullable|date|before:today',
            'email'         => 'email|unique:customers,email,' . $customer->id,
            'phone'         => 'digits:8|unique:customers,phone,' . $customer->id,
            'password'      => 'min:6',
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }
        $data = request()->only('first_name', 'last_name', 'gender', 'date_of_birth', 'email', 'password','phone');

        if (! isset($data['password']) || ! $data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $updatedCustomer = $this->customerRepository->update($data, $customer->id);

        return response()->json([
            'message' => 'Your account has been updated successfully.',
            'data'    => new CustomerResource($updatedCustomer),
        ]);
    }

    public function update_password(Request $request){
        $validation = Validator::make($request->all(), [
            'phone'      => 'required|digits:8',
            'password'   => 'required|min:6',
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        $updatedCustomer = $this->customerRepository->getModel()
            ->where('phone', $request->input('phone'))
            ->update(['password'=>bcrypt($request->input('password'))]);

        if($updatedCustomer){
            return response()->json([
                'message' => 'Password updated successfully.',
                'success'    => true,
            ]);
        }

        return response()->json([
            'message' => 'Password update unsuccessfull',
            'success'    => false,
        ]);
    }
}