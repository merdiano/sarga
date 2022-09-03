<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sarga\Brand\Models\BrandProxy;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Core\Eloquent\TranslatableModel;
use Sarga\Shop\Contracts\Menu as MenuContract;

class Menu extends TranslatableModel implements MenuContract
{
    protected $fillable = [
        'position',
        'status',
        'filter',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    public function brands() :BelongsToMany{
        return $this->belongsToMany(BrandProxy::modelClass(),'menu_brands');
    }

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(CategoryProxy::modelClass(), 'menu_categories');
    }
}