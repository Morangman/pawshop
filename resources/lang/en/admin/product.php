<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Products',
        'create' => 'Create product',
        'edit' => 'Edit product',
    ],
    'form' => [
        'name' => 'Name*',
        'image' => 'Image*',
        'faq' => 'FAQ',
        'subcategory' => 'Parent category',
        'status' => 'Status',
        'cart_count' => 'Add to cart quantity',
        'view_count' => 'Count of views',
        'text' => 'Cash in up to..',
        'premium_price' => 'Premium price for device',
        'price_for_broken' => 'Price for broken device',
        'steps' => 'Steps for device',
        'select_step' => 'Select step',
        'hidden' => 'Is hidden',
    ],
    'index' => [
        'title' => 'Products',
        'header_btn' => 'Create product',
        'filters' => [
            'search' => 'Search products by typing one of these fields: name',
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
        'title' => 'Create product',
    ],
    'edit' => [
        'title' => 'Edit product',
    ],
    'delete' => [
        'title' => 'Delete product',
    ],
    'messages' => [
        'create' => 'Product has been successfully created',
        'update' => 'Product has been successfully updated',
        'delete' => 'Product has been successfully deleted',
        'image_delete' => 'Image has been successfully deleted',
    ],
];
