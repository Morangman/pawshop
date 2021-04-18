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
        'faq' => 'FAQ',
        'subcategory' => 'Parent category',
        'status' => 'Status',
        'cart_count' => 'Add to cart quantity',
        'view_count' => 'Number of views',
        'text' => 'Cash in up to..',
        'premium_price' => 'Premium price for device',
        'price_for_broken' => 'Price for broken device',
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
