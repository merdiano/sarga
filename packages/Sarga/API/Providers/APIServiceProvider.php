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
        include __DIR__ . '/../Http/helpers.php';
        
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
    }
}