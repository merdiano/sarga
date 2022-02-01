<?php

namespace Sarga\Shop\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
class VendorProductRepository extends Repository
{

    public function __construct(
        App $app
    ){
        parent::__construct($app);
    }
    public function model()
    {
        return 'Webkul\Marketplace\Contracts\Product';
    }

}