<?php namespace Sarga\Brand\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Sarga\Brand\Contracts\Brand as BrandContract;

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
}