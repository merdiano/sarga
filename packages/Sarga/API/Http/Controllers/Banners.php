<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Core\Slider;
use Webkul\Core\Repositories\SliderRepository;

class Banners extends \Webkul\RestApi\Http\Controllers\V1\Shop\Core\CoreController
{
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return Slider::class;
    }

    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return SliderRepository::class;
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