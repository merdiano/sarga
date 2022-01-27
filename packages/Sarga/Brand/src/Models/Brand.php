<?php namespace Sarga\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Sarga\Brand\Contracts\Brand as BrandContract;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Marketplace\Models\SellerProxy;
use Webkul\Product\Models\ProductProxy;

class Brand extends Model implements BrandContract
{
    protected $fillable = [
        'position',
        'status',
        'code',
        'name'
    ];

    /**
     * Get image url for the category image.
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * The products that belong to the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductProxy::modelClass(), 'brand_id');
    }

    public function sellers():BelongsToMany
    {
        return $this->belongsToMany(SellerProxy::modelClass(), 'seller_brands');
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'category_brands');
    }
}