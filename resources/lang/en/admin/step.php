<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Step',
        'create' => 'Create step',
        'edit' => 'Edit step',
    ],
    'form' => [
        'name' => 'Name*',
        'title' => 'Title*',
        'items' => 'Items*',
        'item_text' => 'Item text',
        'is_condition' => 'This is condition?',
        'is_checkboxes' => 'This is checkboxes?',
        'is_functional' => 'This is functional?',
        'price_plus' => 'Price for item (Device price + or - this price)',
        'price_percent' => 'Percentage of the price (Device price + or - this percent)',
        'tip' => 'Select tip',
    ],
    'index' => [
        'title' => 'Step',
        'header_btn' => 'Create step',
        'filters' => [
            'search' => 'Search steps by typing one of these fields: name',
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
        'title' => 'Create step',
    ],
    'edit' => [
        'title' => 'Edit step',
    ],
    'delete' => [
        'title' => 'Delete step',
    ],
    'messages' => [
        'create' => 'Step has been successfully created',
        'update' => 'Step has been successfully updated',
        'delete' => 'Step has been successfully deleted',
    ],
];
