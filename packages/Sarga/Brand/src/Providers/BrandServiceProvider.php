<?php namespace Sarga\Brand\Providers;

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