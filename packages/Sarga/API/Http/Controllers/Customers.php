<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Webkul\API\Http\Controllers\Shop\SessionController;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;


class Customers extends SessionController
{
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->middleware('auth:' . $this->guard, ['only' => ['get', 'update', 'destroy']]);

        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request,CustomerGroupRepository $groupRepository)
    {

        $validation = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'phone'      => 'required|digits:8|unique:customers,phone',
            'password'   => 'required|min:6',
            'gender'     => 'in:Male,Female'
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        $data = [
            'first_name'  => $request->get('first_name'),
            'last_name'   => $request->get('last_name'),
            'phone'       => $request->get('phone'),
            'password'    => bcrypt($request->get('password')),
            'channel_id'  => core()->getCurrentChannel()->id,
            'is_verified' => 1,
            'gender'      => $request->get('gender'),
            'customer_group_id' => $groupRepository->findOneWhere(['code' => 'general'])->id
        ];

        Event::dispatch('customer.registration.before');

        $customer = $this->customerRepository->create($data);

        Event::dispatch('customer.registration.after', $customer);

        if (! $jwtToken = auth()->guard($this->guard)->attempt($request->only(['phone', 'password']))) {
            return response()->json([
                'error' => 'Invalid Email or Password',
            ], 401);
        }

        Event::dispatch('customer.after.login', $request->get('phone'));

        return response()->json([
            'token'   => $jwtToken,
            'message' => 'Logged in successfully.',
            'data'    => new CustomerResource($customer),
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
        ]);

        if ($validation->fails()) {

            return response()->json(['errors'=>$validation->getMessageBag()->all()],422);
        }

        $jwtToken = null;

        if (! $jwtToken = auth()->guard($this->guard)->attempt($request->only(['phone', 'password']))) {
            return response()->json([
                'error' => 'Invalid Email or Password',
            ], 401);
        }

        Event::dispatch('customer.after.login', $request->get('phone'));

        $customer = auth($this->guard)->user();

        return response()->json([
            'token'   => $jwtToken,
            'message' => 'Logged in successfully.',
            'data'    => new CustomerResource($customer),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $customer = auth($this->guard)->user();

        $validation = Validator::make(request(), [
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
}