<?php

namespace Sarga\Shop\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Models\Address;
use Sarga\Contract\Recipient as RecipientContract;
use Webkul\Customer\Database\Factories\CustomerAddressFactory;

class Recipient extends Address implements RecipientContract
{
    use HasFactory;

    public const ADDRESS_TYPE = 'recipient';

    /**
     * @var array default values
     */
    protected $attributes = [
        'address_type' => self::ADDRESS_TYPE,
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        static::addGlobalScope('address_type', static function (Builder $builder) {
            $builder->where('address_type', self::ADDRESS_TYPE);
        });

        parent::boot();
    }

    /**
     * Create a new factory instance for the model
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CustomerAddressFactory::new();
    }
}