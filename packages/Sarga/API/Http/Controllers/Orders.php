<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Customer\OrderResource;
use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\OrderController;


class Orders extends OrderController
{
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return OrderResource::class;
    }
}