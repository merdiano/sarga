<?php

namespace Sarga\Shop\Models;

class Order extends \Webkul\Sales\Models\Order implements \Sarga\Shop\Contracts\Order
{
    public const STATUS_PURCHASE = 'purchase';

    public const STATUS_SHIPPING = 'shipping';

    protected $statusLabel = [
        self::STATUS_PENDING         => 'Pending',
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_PURCHASE        => 'Accepted',
        self::STATUS_SHIPPING        => 'Shipping',
        self::STATUS_PROCESSING      => 'Arrived',
        self::STATUS_COMPLETED       => 'Completed',
        self::STATUS_CANCELED        => 'Canceled',
        self::STATUS_CLOSED          => 'Closed',
        self::STATUS_FRAUD           => 'Fraud',

    ];

    public function canSendShip(){
        return $this->status === self::STATUS_PURCHASE;
    }

    public function canAccept(){
        return $this->status === self::STATUS_PENDING;
    }
    /**
     * Checks if new invoice is allow or not
     *
     * @return bool
     */
    public function canInvoice(): bool{
        return $this->status === self::STATUS_SHIPPING && parent::canInvoice();
    }

 }