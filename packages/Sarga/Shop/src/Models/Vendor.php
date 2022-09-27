<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Sarga\Brand\Models\BrandProxy;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerCategoryProxy;
use Sarga\Shop\Contracts\Vendor as VendorContract;

class Vendor extends Seller implements VendorContract
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
        return $this->belongsToMany(MenuProxy::modelClass(),'seller_menus','seller_id');
    }
}