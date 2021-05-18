<?php

declare(strict_types = 1);

use App\Warehouse;

return [
    'breadcrumbs' => [
        'index' => 'Warehouse',
        'create' => 'Add product',
        'edit' => 'Edit product',
    ],
    'form' => [
        'category_id' => 'Product ID',
        'order_id' => 'Order ID',
        'status' => 'Status',
        'product_name' => 'Device name',
        'imei' => 'Device IMEI',
        'serial_number' => 'Device Serial Number',
        'price' => 'Device Price',
        'clear_price' => 'Device deducted price',
        'is_locked' => 'Is device locked',
        'delivery_price' => 'Delivery price',
        'repair_price' => 'Repair price',
        'sell_price' => 'Sell price',
        'steps' => 'Selected steps',
        'images' => 'Product images',
    ],
    'index' => [
        'title' => 'Warehouse',
        'header_btn' => 'Add product',
        'filters' => [
            'search' => 'Search by typing product name, order ID, imei or serial number',
        ],
        'table' => [
            'headers' => [
                'photo' => 'Photo',
                'product_name' => 'Device name',
                'status' => 'Status',
                'imei' => 'IMEI',
                'serial_number' => 'Device Serial Number',
                'clear_price' => 'Device price',
                'delivery_price' => 'Delivery price',
                'repair_price' => 'Repair price',
                'sell_price' => 'Sell price',
            ],
        ],
        'search' => [
            'all' => 'All',
        ],
    ],
    'product_statuses' => [
        Warehouse::STATUS_PAID => 'Paid',
        Warehouse::STATUS_IN_REPAIR => 'In repair',
        Warehouse::STATUS_FOR_SALE => 'For sale',
        Warehouse::STATUS_SALED => 'Saled',
    ],
    'create' => [
        'title' => 'Add product',
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
        'media-delete' => 'Image has been successfully deleted',
    ],
];
