<?php

return [
//    [
//        'key'    => 'sales.paymentmethods.terminal',
//        'name'   => 'Terminal',
//        'sort'   => 3,
//        'fields' => [
//            [
//                'name'          => 'title',
//                'title'         => 'admin::app.admin.system.title',
//                'type'          => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//                'channel_based' => true,
//                'locale_based'  => true,
//            ], [
//                'name'          => 'description',
//                'title'         => 'admin::app.admin.system.description',
//                'type'          => 'textarea',
//                'channel_based' => true,
//                'locale_based'  => true,
//            ], [
//                'name'          => 'instructions',
//                'title'         => 'admin::app.admin.system.instructions',
//                'type'          => 'textarea',
//                'channel_based' => true,
//                'locale_based'  => true,
//            ], [
//                'name'          => 'generate_invoice',
//                'title'         => 'admin::app.admin.system.generate-invoice',
//                'type'          => 'boolean',
//                'default_value' => false,
//                'channel_based' => true,
//                'locale_based'  => false,
//            ], [
//                'name'          => 'invoice_status',
//                'title'         => 'admin::app.admin.system.set-invoice-status',
//                'validation'    => 'required_if:generate_invoice,1',
//                'type'          => 'select',
//                'options'       => [
//                    [
//                        'title' => 'admin::app.sales.invoices.status-pending',
//                        'value' => 'pending',
//                    ], [
//                        'title' => 'admin::app.sales.invoices.status-paid',
//                        'value' => 'paid',
//                    ],
//                ],
//                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
//                'channel_based' => true,
//                'locale_based'  => false,
//            ], [
//                'name'          => 'order_status',
//                'title'         => 'admin::app.admin.system.set-order-status',
//                'type'          => 'select',
//                'options'       => [
//                    [
//                        'title' => 'admin::app.sales.orders.order-status-pending',
//                        'value' => 'pending',
//                    ], [
//                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
//                        'value' => 'pending_payment',
//                    ], [
//                        'title' => 'admin::app.sales.orders.order-status-processing',
//                        'value' => 'processing',
//                    ],
//                ],
//                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
//                'channel_based' => true,
//                'locale_based'  => false,
//            ], [
//                'name'          => 'active',
//                'title'         => 'admin::app.admin.system.status',
//                'type'          => 'boolean',
//                'validation'    => 'required',
//                'channel_based' => true,
//                'locale_based'  => true,
//            ], [
//                'name'    => 'sort',
//                'title'   => 'admin::app.admin.system.sort_order',
//                'type'    => 'select',
//                'options' => [
//                    [
//                        'title' => '1',
//                        'value' => 1,
//                    ], [
//                        'title' => '2',
//                        'value' => 2,
//                    ], [
//                        'title' => '3',
//                        'value' => 3,
//                    ], [
//                        'title' => '4',
//                        'value' => 4,
//                    ],
//                ],
//            ],
//        ],
//    ],
    [
        'key' => 'sales.paymentmethods.altynasyr',
        'name' => 'admin::app.admin.system.altyn-asyr',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'admin::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'admin::app.admin.system.description',
                'type' => 'textarea',
                'locale_based' => true
            ],  [
                'name' => 'business_account',
                'title' => 'admin::app.admin.system.business-account',
                'type' => 'text',
                'validation' => 'required'
            ],[
                'name' => 'account_password',
                'title' => 'admin::app.account.password',
                'type' => 'password',
                'validation' => 'required'
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',

            ]
        ]
    ],
    [
        'key' => 'sales.paymentmethods.tfeb',
        'name' => 'TFEB',
        'sort' => 5,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'admin::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'locale_based' => true
            ], [
                'name' => 'description',
                'title' => 'admin::app.admin.system.description',
                'type' => 'textarea',
                'locale_based' => true
            ],  [
                'name' => 'client_id',
                'title' => 'ClientID',
                'type' => 'text',
                'validation' => 'required'
            ],[
                'name' => 'client_secret',
                'title' => 'Client Secret',
                'type' => 'text',
                'validation' => 'required'
            ],[
                'name' => 'merchant',
                'title' => 'Merchant',
                'type' => 'text',
                'validation' => 'required'
            ],[
                'name' => 'terminal',
                'title' => 'Terminal',
                'type' => 'text',
                'validation' => 'required'
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',

            ]
        ]
    ]
];