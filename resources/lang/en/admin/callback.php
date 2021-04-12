<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Callbacks',
        'create' => 'Create Callback',
        'edit' => 'Edit Callback',
    ],
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'text' => 'Text',
    ],
    'index' => [
        'title' => 'Callbacks',
        'header_btn' => 'Create Callback',
        'filters' => [
            'search' => 'Search callbacks by typing one of these fields: name',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'text' => 'Text',
                'created_at' => 'Created date',
            ],
        ],
    ],
    'create' => [
        'title' => 'Create callback',
    ],
    'edit' => [
        'title' => 'Edit callback',
    ],
    'delete' => [
        'title' => 'Delete callback',
    ],
    'messages' => [
        'create' => 'Callback has been successfully created',
        'update' => 'Callback has been successfully updated',
        'delete' => 'Callback has been successfully deleted',
    ],
];
