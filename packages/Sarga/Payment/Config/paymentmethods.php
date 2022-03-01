<?php
return [
    'cashbeforedelivery' =>[
        'code'        => 'cashbeforedelivery',
        'title'       => "I\\'ll pay by cash with deposit 100%",
        'description' => 'Pay deposit 100% to gain 10% discount',
        'class'       => 'Payment\Methods\Terminal',
        'active'      => true,
        'sort'        => 2,
    ],
    'terminal'  => [
        'code'        => 'terminal',
        'title'       => "I\\'ll pay by card on terminal",
        'description' => '50% deposit is taken before delivery',
        'class'       => 'Payment\Methods\Terminal',
        'active'      => true,
        'sort'        => 3,
    ],
    'altynasyr' =>[
        'code' => 'altynasyr',
        'title' => "I\\'ll pay by Altyn Asyr card",
        'description' => 'Altyn Asyr Kartly Töleg',
        'api_url' => 'https://mpi.gov.tm/payment/rest/',
        'class' => 'Sarga\Payment\Methods\AltynAsyr',
        'active' => false,
        'sort' => 4
    ],
    'tfeb' =>[
        'code' => 'tfeb',
        'title' =>"I\\'ll pay by TFEB card",
        'description' => 'THE STATE BANK FOR FOREIGN ECONOMIC AFFAIRS OF TURKMENISTAN',
        'api_url' => 'https://ecomt.tfeb.gov.tm/v1/orders/',
        'class' => 'Sarga\Payment\Methods\TFEB',
        'active' => false,
        'sort' => 5
    ]
];