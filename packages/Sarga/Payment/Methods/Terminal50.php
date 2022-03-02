<?php

namespace Sarga\Payment\Methods;

use Webkul\Payment\Payment\Payment;

class Terminal50 extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'terminal50';
    public function getRedirectUrl()
    {
        // TODO: Implement getRedirectUrl() method.
    }
}