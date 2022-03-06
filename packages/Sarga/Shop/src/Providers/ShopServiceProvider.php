<?php

namespace Sarga\Shop\Providers;

use Illuminate\Foundation\AliasLoader;
use Sarga\Shop\ShoppingCart;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Tree;
use Illuminate\Routing\Router;
use Illuminate\Pagination\Paginator;
use Webkul\Shop\Http\Middleware\Theme;
use Illuminate\Support\ServiceProvider;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\Currency;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        /* publishers */
//        $this->publishes([
//            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
//            __DIR__ . '/../Resources/views'       => resource_path('themes/default/views'),
//            __DIR__ . '/../Resources/lang'        => resource_path('lang/vendor/shop'),
//        ]);

        /* loaders */
//        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
//        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
//        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'shop');
//        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shop');

        /* aliases */
//        $router->aliasMiddleware('locale', Locale::class);
//        $router->aliasMiddleware('theme', Theme::class);
//        $router->aliasMiddleware('currency', Currency::class);

        /* view composers */
//        $this->composeView();

        /* paginators */
//        Paginator::defaultView('shop::partials.pagination');
//        Paginator::defaultSimpleView('shop::partials.pagination');

        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
//        $this->registerConfig();
    }
    /**
     * Register cart as a singleton.
     *
     * @return void
     */
    protected function registerFacades(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('shoppingcart', ShoppingCart::class);

        $this->app->singleton('shoppingcart', function () {
            return new ShoppingCart();
        });

        $this->app->bind('shoppingcart', ShoppingCart::class);
    }
    /**
     * Bind the the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
            $tree = Tree::create();

            $menu = config('menu.customer');

            foreach ($menu as $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
