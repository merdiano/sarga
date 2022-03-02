<?php

namespace Sarga\Payment\Methods;

use Webkul\Payment\Payment\Payment;

class Cash50 extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'cash50';
    public function getRedirectUrl()
    {
        // TODO: Implement getRedirectUrl() method.
    }
}