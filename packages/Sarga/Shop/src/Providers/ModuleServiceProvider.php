<?php

namespace Sarga\Shop\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Sarga\Shop\Models\Recipient::class,
    ];
}