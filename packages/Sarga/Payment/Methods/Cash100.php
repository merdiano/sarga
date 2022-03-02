<?php

namespace Sarga\Payment\Methods;

use Webkul\Payment\Payment\Payment;

class Cash100 extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cashbeforedelivery';

    public function getRedirectUrl()
    {

    }
}