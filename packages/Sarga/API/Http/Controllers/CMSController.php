<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Core\CMSResource;
use Webkul\CMS\Repositories\CmsRepository;
use Webkul\RestApi\Http\Controllers\V1\Shop\Core\CoreController;

class CMSController extends CoreController
{
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return CMSResource::class;
    }

    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return CmsRepository::class;
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