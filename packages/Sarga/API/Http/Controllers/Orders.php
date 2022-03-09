<?php

namespace Sarga\API\Http\Controllers;

use Webkul\RestApi\Http\Controllers\V1\Shop\Customer\OrderController;
use Webkul\RestApi\Http\Resources\V1\Shop\Sales\OrderResource;

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