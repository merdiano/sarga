<?php

namespace Sarga\Shop\Repositories;

use Sarga\Shop\Models\Vendor;
use Webkul\Marketplace\Repositories\SellerRepository;

class VendorRepository extends SellerRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return Vendor::class;
    }

}