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

//        CategoryProxy::observe(CategoryObserver::class);
    }
}