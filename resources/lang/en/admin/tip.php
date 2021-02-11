<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Tip',
        'create' => 'Create tip',
        'edit' => 'Edit tip',
    ],
    'form' => [
        'name' => 'Name*',
        'text' => 'Text*',
    ],
    'index' => [
        'title' => 'Tip',
        'header_btn' => 'Create tip',
        'filters' => [
            'search' => 'Search tips by typing one of these fields: name',
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
        'title' => 'Create tip',
    ],
    'edit' => [
        'title' => 'Edit tip',
    ],
    'delete' => [
        'title' => 'Delete tip',
    ],
    'messages' => [
        'create' => 'Tip has been successfully created',
        'update' => 'Tip has been successfully updated',
        'delete' => 'Tip has been successfully deleted',
    ],
];
