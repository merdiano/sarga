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
        $this->loadRoutesFrom(__DIR__ . '/../Routes/settings-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sarga_admin');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
