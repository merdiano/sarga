<?php

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ScrapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
        $this->mergeConfigFrom(__DIR__ . '/../Config/scrap.php');

    }
}