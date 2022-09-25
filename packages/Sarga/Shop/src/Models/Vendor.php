<?php

namespace Sarga\Shop\Models;

use Sarga\Brand\Models\BrandProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerCategory;
use Webkul\Marketplace\Models\SellerCategoryProxy;

class Vendor extends Seller
{
    public function categories() : HasOne
    {
        return $this->hasOne(SellerCategoryProxy::modelClass(),'seller_id',);
    }

    public function brands() : BelongsToMany
    {
        return $this->belongsToMany(BrandProxy::modelClass(),'seller_brands','seller_id');
    }

    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(MenuProxy::modelClass(),'seller_menus','seller_id')
    }
}