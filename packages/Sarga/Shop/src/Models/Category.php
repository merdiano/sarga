<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Sarga\Brand\Models\BrandProxy;
use Webkul\Category\Database\Factories\CategoryFactory;
use Webkul\Category\Models\Category as WCategory;
class Category extends WCategory implements \Sarga\Shop\Contracts\Category
{
    /**
     * Fillables.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
        'trendyol_url',
        'lcw_url',
        'default_weight',
        'product_limit'
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['image_url', 'category_icon_url'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return CategoryFactory::new ();
    }

    public function brands() :BelongsToMany{
        return $this->belongsToMany(BrandProxy::modelClass(),'category_brands');
    }

    public function vendors() :BelongsToMany{
        return $this->belongsToMany(VendorProxy::modelClass(),'vendor_categories');
    }
}