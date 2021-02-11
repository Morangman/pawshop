<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Categories',
        'create' => 'Create category',
        'edit' => 'Edit category',
    ],
    'form' => [
        'name' => 'Name*',
        'image' => 'Image*',
        'subcategory' => 'Parent category',
        'status' => 'Status',
        'text' => 'Cash in up to..',
        'steps' => 'Steps for device',
        'select_step' => 'Select step',
        'hidden' => 'Is hidden',
    ],
    'index' => [
        'title' => 'Categories',
        'header_btn' => 'Create category',
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
        'title' => 'Create category',
    ],
    'edit' => [
        'title' => 'Edit category',
    ],
    'delete' => [
        'title' => 'Delete category',
    ],
    'messages' => [
        'create' => 'Category has been successfully created',
        'update' => 'Category has been successfully updated',
        'delete' => 'Category has been successfully deleted',
        'image_delete' => 'Image has been successfully deleted',
    ],
];
