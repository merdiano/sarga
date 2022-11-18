<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sarga\Brand\Models\BrandProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Marketplace\Models\Seller;
use Sarga\Shop\Contracts\Vendor as VendorContract;

class Vendor extends Seller implements VendorContract
{
    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(CategoryProxy::modelClass(),'vendor_categories',);
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