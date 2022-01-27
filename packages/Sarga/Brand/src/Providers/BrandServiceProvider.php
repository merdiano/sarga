<?php namespace Sarga\Brand\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/brand-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'brand');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'brand');
//        Log::info('brandd service provider');
        $this->app->register(EventServiceProvider::class);
//        CategoryProxy::observe(CategoryObserver::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php',
            'menu.admin'
        );
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/concord.php','concord.modules');
    }
}