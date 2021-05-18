<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Warehouse statuses',
        'create' => 'Create status',
        'edit' => 'Edit status',
    ],
    'form' => [
        'name' => 'Name*',
        'color' => 'Bage color',
        'order' => 'Order',
    ],
    'index' => [
        'title' => 'Warehouse statuses',
        'header_btn' => 'Create status',
        'filters' => [
            'search' => 'Search statuses by typing one of these fields: name',
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
        'title' => 'Create status',
    ],
    'edit' => [
        'title' => 'Edit status',
    ],
    'delete' => [
        'title' => 'Delete status',
    ],
    'messages' => [
        'create' => 'Status has been successfully created',
        'update' => 'Status has been successfully updated',
        'delete' => 'Status has been successfully deleted',
    ],
];
