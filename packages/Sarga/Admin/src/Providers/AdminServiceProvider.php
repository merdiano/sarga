<?php

namespace Sarga\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Sarga\Admin\Console\Commands\UpdateMeilisearchIndex;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sarga_admin');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sarga');

        $this->publishes([
            __DIR__ . '/../Resources/views/catalog' => resource_path('views/vendor/admin/catalog'),
            __DIR__ . '/../Resources/views/notifications' => resource_path('views/vendor/admin/notifications'),
            __DIR__ . '/../Resources/views/customers/addresses' => resource_path('views/vendor/admin/customers/addresses'),
            __DIR__ . '/../Resources/views/sales' => resource_path('views/vendor/admin/sales'),
            __DIR__ . '/../Resources/views/customers/edit.blade.php' => resource_path('views/vendor/admin/customers/edit.blade.php'),
        ],'admin');

        $this->publishes([ __DIR__ . '/../Config/push.php' => config_path('push.php')],'sarga_config');

        $this->app->register(EventServiceProvider::class);

    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php', 'carriers'
        );

        $this->commands([
            UpdateMeilisearchIndex::class
        ]);
    }
}
