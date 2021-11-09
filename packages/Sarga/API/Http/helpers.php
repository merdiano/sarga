<?php
if (! function_exists('currency')) {
    /**
     * Core helper.
     *
     * @return \Webkul\Core\Core
     */
    function currency($amount = 0)
    {
        $core =  app()->make(\Webkul\Core\Core::class);
        if (is_null($amount)) {
            $amount = 0;
        }

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($core->convertPrice($amount), $core->getCurrentCurrency()->code);

    }
}