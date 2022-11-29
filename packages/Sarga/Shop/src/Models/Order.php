<?php

namespace Sarga\Shop\Models;

class Order extends \Webkul\Sales\Models\Order implements \Sarga\Shop\Contracts\Order
{
    public const STATUS_PURCHASE = 'purchase';

    public const STATUS_SHIPPING = 'shipping';

    protected $statusLabel = [
        self::STATUS_PENDING         => 'pending',
        self::STATUS_PENDING_PAYMENT => 'pending_payment',
        self::STATUS_PURCHASE        => 'accepted',
        self::STATUS_SHIPPING        => 'shipping',
        self::STATUS_PROCESSING      => 'arrived',
        self::STATUS_COMPLETED       => 'completed',
        self::STATUS_CANCELED        => 'canceled',
        self::STATUS_CLOSED          => 'closed',
        self::STATUS_FRAUD           => 'fraud',

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