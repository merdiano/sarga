<?php

return [
    'pickup' => [
        'code'             => 'pickup',
        'title'            => 'Pickup',
        'description'      => 'Pickup from address',
        'active'           => true,
        'is_calculate_tax' => false,
        'weight_price'     => '10',
        'type'             => 'per_unit',
        'class'            => 'Sarga\Admin\Shipment\Pickup',
    ],

    'courier'             => [
        'code'             => 'courier',
        'title'            => 'Courier',
        'description'      => 'Courier Shipping',
        'active'           => true,
        'is_calculate_tax' => false,
        'weight_price'     => '10',
        'class'            => 'Sarga\Admin\Shipment\Courier',
    ]
];
