<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 12/29/2021
 * Time: 10:14
 */

namespace Sarga\Admin\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{

    public function boot(){
//        Event::listen('bagisto.admin.catalog.category.create_form_accordian.general.after', function($viewRenderEventManager) {
//            $viewRenderEventManager->addTemplate('sarga_admin::catalog.categories.scrap.create');
//        });
//
//        Event::listen('bagisto.admin.catalog.category.edit_form_accordian.general.after', function($viewRenderEventManager) {
//            $viewRenderEventManager->addTemplate('sarga_admin::catalog.categories.scrap.edit');
//        });
    }
}