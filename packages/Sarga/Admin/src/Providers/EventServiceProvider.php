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
use Sarga\Admin\Listeners\Customer;
use Sarga\Admin\Listeners\Notification;

class EventServiceProvider extends ServiceProvider
{

    public function boot(){
        Event::listen('customer.registration.after', [Customer::class,'register']);
        Event::listen('checkout.order.save.after', 'Webkul\Marketplace\Listeners\Order@afterPlaceOrder');
        Event::listen('sales.order.update-status.item', [Notification::class,'orderItem']);
//        select engine, count(*) as TABLES, concat(round(sum(table_rows)/1000000,2),'M') as rowlar, concat(round(sum(data_length)/(1024*1024*1024),2),'G') as DATA, concat(round(sum(index_length)/(1024*1024*1024),2),'G') as idx from information_schema.TABLES where table_schema not in('mysql', 'merformance_schema', 'information_schema') group by engine order by sum(data_length+index_length) desc limit 10;
//        Event::listen('bagisto.admin.catalog.category.create_form_accordian.general.after', function($viewRenderEventManager) {
//            $viewRenderEventManager->addTemplate('sarga_admin::catalog.categories.scrap.create');
//        });
//
//        Event::listen('bagisto.admin.catalog.category.edit_form_accordian.general.after', function($viewRenderEventManager) {
//            $viewRenderEventManager->addTemplate('sarga_admin::catalog.categories.scrap.edit');
//        });
//        select engine, count(*) as TABLES, concat(round(sum(table_rows)/1000000,2),'M') rowlar   from information_schema.TABLES where table_schema not in('mysql', 'merformance_schema', 'information_schema') group by engine order by sum(data_length+index_length) desc limit 10;
    }
}