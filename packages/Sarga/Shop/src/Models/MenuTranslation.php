<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Sarga\Shop\Contracts\MenuTranslation as MenuTranslationContract;
class MenuTranslation extends Model implements MenuTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'locale_id',
    ];
}