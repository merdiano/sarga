<?php

namespace Sarga\Brand\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen('bagisto.admin.catalog.product.edit_form_accordian.additional_views.before', function($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('brand::admin.catalog.products.fields.brand');

        });
    }
}