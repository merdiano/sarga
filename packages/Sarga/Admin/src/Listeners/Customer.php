<?php

namespace Sarga\Admin\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Sarga\Admin\Http\Resources\CustomerResource;
use Webkul\Customer\Models\Customer as CustomerModel;

class Customer implements ShouldQueue
{
    public $delay = 60;
    public $queue = 'redis';

    public function register(CustomerModel $customer){

        try{
            Http::post(env('HTTP_MANAGER_ADDRESS','https://panel.sargagroup.cf/api').'/customer',CustomerResource::make($customer));

        }catch(\Exception $e){
            Log::error($e);
        }

    }
}