<?php

namespace Sarga\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\Admin\Http\Middleware\Locale;
use Webkul\Core\Tree;

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
        $this->app->register(EventServiceProvider::class);

    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );
    }
}
