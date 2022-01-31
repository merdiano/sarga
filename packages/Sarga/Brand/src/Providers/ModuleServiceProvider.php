<?php

namespace Sarga\Brand\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Sarga\Brand\Models\Brand::class,
    ];
}