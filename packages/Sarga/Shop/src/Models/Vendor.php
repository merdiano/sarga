<?php

namespace Sarga\Shop\Models;

use Webkul\Category\Models\CategoryProxy;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerCategory;
use Webkul\Marketplace\Models\SellerCategoryProxy;

class Vendor extends Seller
{
    public function categories(){
        return $this->hasOne(SellerCategoryProxy::modelClass(),'seller_id',);
    }
}