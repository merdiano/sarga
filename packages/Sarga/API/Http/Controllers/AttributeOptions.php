<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\Shop\Repositories\AttributeOptionRepository;

class AttributeOptions extends \Webkul\RestApi\Http\Controllers\V1\Shop\ResourceController
{
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale'];
    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return AttributeOptionRepository::class;
    }
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return AttributeOption::class;
    }

    /**
     * Is resource authorized.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return false;
    }
}