<?php

return [
    [
        'key'    => 'sales.paymentmethods.cash100',
        'name'   => 'Nakit 100',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.admin.system.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ],
    [
        'key'    => 'sales.paymentmethods.cash50',
        'name'   => 'Nakit 50',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.admin.system.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ],
    [
        'key'    => 'sales.paymentmethods.terminal100',
        'name'   => 'Terminal 100',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.admin.system.instructions',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.paymentmethods.terminal50',
        'name'   => 'Terminal 50',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.admin.system.instructions',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.admin.system.sort_order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ],
    [
        'key' => 'sales.paymentmethods.altynasyr',
        'name' => 'Online AltynAsyr',
        'sort' => 4,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name' => 'description',
                'title' => 'admin::app.admin.system.description',
                'type' => 'textarea',
                'locale_based' => true
            ],  [
                'name' => 'business_account',
                'title' => 'admin::app.admin.system.business-account',
                'type' => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],[
                'name' => 'account_password',
                'title' => 'admin::app.account.password',
                'type' => 'depends',
                'depend'        => 'active:1',
                'validation'    => 'required_if:active,1',
            ],[
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.admin.system.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => false,
                'locale_based'  => false,
            ],[
                'name'          => 'invoice_status',
                'title'         => 'admin::app.admin.system.set-invoice-status',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.invoices.status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.invoices.status-paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.admin.system.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.sales.orders.order-status-pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.sales.orders.order-status-processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.admin.system.generate-invoice-applicable',
                'channel_based' => false,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',

            ]
        ]
    ],
//    [
//        'key' => 'sales.paymentmethods.tfeb',
//        'name' => 'Online TFEB',
//        'sort' => 5,
//        'fields' => [
//            [
//                'name'          => 'title',
//                'title'         => 'admin::app.admin.system.title',
//                'type'          => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//                'channel_based' => false,
//                'locale_based'  => true,
//            ], [
//                'name' => 'description',
//                'title' => 'admin::app.admin.system.description',
//                'type' => 'textarea',
//                'locale_based' => true
//            ],  [
//                'name' => 'client_id',
//                'title' => 'ClientID',
//                'type' => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//            ],[
//                'name' => 'client_secret',
//                'title' => 'Client Secret',
//                'type' => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//            ],[
//                'name' => 'merchant',
//                'title' => 'Merchant',
//                'type' => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//            ],[
//                'name' => 'terminal',
//                'title' => 'Terminal',
//                'type' => 'depends',
//                'depend'        => 'active:1',
//                'validation'    => 'required_if:active,1',
//            ],[
//                'name'          => 'generate_invoice',
//                'title'         => 'admin::app.admin.system.generate-invoice',
//                'type'          => 'boolean',
//                'default_value' => false,
//                'channel_based' => false,
//                'locale_based'  => false,
//            ],[
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
//                'channel_based' => false,
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
//                'channel_based' => false,
//                'locale_based'  => false,
//            ],  [
//                'name'          => 'active',
//                'title'         => 'admin::app.admin.system.status',
//                'type'          => 'boolean',
//                'validation'    => 'required',
//
//            ]
//        ]
//    ]
];