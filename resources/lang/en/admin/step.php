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
        'image' => 'Image*',
        'status' => 'Status',
        'text' => 'Cash in up to..',
        'hidden' => 'Is hidden',
    ],
    'index' => [
        'title' => 'Step',
        'header_btn' => 'Create step',
        'filters' => [
            'search' => 'Search categories by typing one of these fields: name',
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
        'image_delete' => 'Image has been successfully deleted',
    ],
];
