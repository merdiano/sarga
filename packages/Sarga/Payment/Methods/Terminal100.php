<?php

namespace Sarga\Payment\Methods;

use Webkul\Payment\Payment\Payment;

class Terminal100 extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'terminal100';

    public function getRedirectUrl()
    {

    }
}