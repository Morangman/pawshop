<?php

declare(strict_types = 1);

use App\Order;

return [
    'breadcrumbs' => [
        'index' => 'Orders',
        'create' => 'Create order',
        'edit' => 'Edit order',
    ],
    'form' => [
        'user' => 'User',
        'fedex_label' => 'User',
        'fedex_label' => 'FedEx label',
        'name' => 'Name',
        'phone' => 'Phone',
        'email' => 'Email',
        'city' => 'City',
        'state' => 'State',
        'address_1' => 'Address 1',
        'address_2' => 'Address 2',
        'postal_code' => 'Postal code',
        'notes' => 'Notes',
        'ordered_product' => 'Ordered products',
        'ordered_status' => 'Ordered status',
        'created_at' => 'Created date',
        'updated_at' => 'Updated date',
        'summ' => 'The amount of ordered products',
        'custom_summ' => 'Custom amount of ordered products',
        'order_number' => 'Order number',
        'add_suspect_ip' => 'Add an IP address to the suspect',
        'suspect_ip' => 'The IP address is marked as suspicious',
        'ordered_products' => [
            'name' => 'Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'unit' => '$',
        ],
        'reminder' => [
            'name' => 'Create reminders',
            'title' => 'The name of the task',
            'text' => 'Comment',
            'date' => 'Date',
        ],
    ],
    'index' => [
        'title' => 'Orders',
        'header_btn' => 'Create order',
        'filters' => [
            'search' => 'Search by typing name, id or tracking number',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'contacts' => 'Contacts',
                'status' => 'Status',
                'label_status' => 'Label created',
                'fedex_status' => 'FedEx status',
                'created_at' => 'Created date',
                'estimate_date' => 'Estimate date',
                'delivered_date' => 'Delivered date',
            ],
        ],
        'search' => [
            'all' => 'All',
        ],
    ],
    'order_statuses' => [
        Order::STATUS_NEW => 'New order',
    ],
    'confirm_statuses' => [
        Order::STATUS_CHANGED => 'Changed',
        Order::STATUS_NOT_CONFIRMED => 'Pending decision',
        Order::STATUS_CONFIRMED => 'Confirmed',
    ],
    'fedex_statuses' => [
        Order::STATUS_SHIPMENT_CREATED => 'Shipment created',
        Order::STATUS_SHIPMENT_SCHEDULED => 'Shipment scheduled',
        Order::STATUS_PICKED_UP => 'Shipment picked up',
        Order::STATUS_IN_TRANSIT => 'Shipment in transit',
        Order::STATUS_DELIVERED => 'Shipment delivered',
    ],
    'fedex_statuses_colored' => [
        Order::STATUS_SHIPMENT_CREATED => '<span class="badge badge-primary">Label created</span>',
    ],
    'create' => [
        'title' => 'Create order',
    ],
    'edit' => [
        'title' => 'Edit order',
    ],
    'delete' => [
        'title' => 'Delete order',
    ],
    'reminder' => [
        'title' => 'Reminder',
        'text' => 'Reminder',
    ],
    'messages' => [
        'create' => 'Order has been successfully created',
        'update' => 'Order has been successfully updated',
        'delete' => 'Order has been successfully deleted',
        'suspect_ip' => 'Ip successfully added to suspects',
    ],
];
