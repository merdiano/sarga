<?php
namespace Sarga\API\Providers;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
    }
}