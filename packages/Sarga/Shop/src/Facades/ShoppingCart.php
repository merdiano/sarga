<?php

namespace Sarga\Shop\Facades;

class ShoppingCart extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shoppingcart';
    }
}