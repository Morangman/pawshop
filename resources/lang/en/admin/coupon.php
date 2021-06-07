<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Coupons',
        'create' => 'Create coupon',
        'edit' => 'Edit coupon',
    ],
    'form' => [
        'category_id' => 'Category*',
        'name' => 'Name*',
        'code' => 'Code*',
        'percent_value' => 'Percent number*',
        'text' => 'Text*',
        'is_hidden' => 'Is hidden',
        'start_date' => 'Start date',
        'end_date' => 'End date',
    ],
    'index' => [
        'title' => 'Coupons',
        'header_btn' => 'Create coupon',
        'filters' => [
            'search' => 'Search coupones by typing one of these fields: name',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'created_at' => 'Created date',
            ],
        ],
    ],
    'create' => [
        'title' => 'Create coupon',
    ],
    'edit' => [
        'title' => 'Edit coupon',
    ],
    'delete' => [
        'title' => 'Delete coupon',
    ],
    'messages' => [
        'create' => 'Coupon has been successfully created',
        'update' => 'Coupon has been successfully updated',
        'delete' => 'Coupon has been successfully deleted',
    ],
];
