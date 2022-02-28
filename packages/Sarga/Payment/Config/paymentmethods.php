<?php
return [
//    'terminal'  => [
//        'code'        => 'terminal',
//        'title'       => 'Terminal',
//        'description' => 'Card On Delivery',
//        'class'       => 'Payment\Methods\Terminal',
//        'active'      => true,
//        'sort'        => 3,
//    ],
    'altynasyr' =>[
        'code' => 'altynasyr',
        'title' => 'Altyn Asyr',
        'description' => 'Altyn Asyr Kartly Töleg',
        'api_url' => 'https://mpi.gov.tm/payment/rest/',
        'class' => 'Sarga\Payment\Methods\AltynAsyr',
        'active' => false,
        'sort' => 4
    ],
    'tfeb' =>[
        'code' => 'tfeb',
        'title' =>'TFEB',
        'description' => 'THE STATE BANK FOR FOREIGN ECONOMIC AFFAIRS OF TURKMENISTAN',
        'api_url' => 'https://ecomt.tfeb.gov.tm/v1/orders/',
        'class' => 'Sarga\Payment\Methods\TFEB',
        'active' => false,
        'sort' => 5
    ]
];