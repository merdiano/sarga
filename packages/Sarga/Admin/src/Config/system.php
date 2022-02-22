<?php
return[
    [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.admin.system.locale-options',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.admin.system.weight-unit',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'lbs',
                        'value' => 'lbs',
                    ], [
                        'title' => 'kgs',
                        'value' => 'kgs',
                    ],
                ],
                'channel_based' => true,
            ],
            [
                'name'          => 'weight_price',
                'title'         => 'Agyrlyk bahasy',
                'type'          => 'number',
                'validate'      => 'required|numeric|min:0',
                'channel_based' => true,
            ],
        ]
    ]
];
