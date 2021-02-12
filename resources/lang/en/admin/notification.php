<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Notification',
        'edit' => 'Edit notification',
    ],
    'form' => [
        'text' => 'Text of notification',
        'order_id' => 'Order:',
        'comment_id' => 'Comment:',
        'created_at' => 'Created date',
    ],
    'index' => [
        'title' => 'Notification',
        'table' => [
            'headers' => [
                'title' => 'Title',
                'created_at' => 'Created date',
                'updated_at' => 'Updated date',
                'common' => [
                    'new' => 'New',
                ],
            ],
        ],
    ],
    'edit' => [
        'title' => 'Edit notification',
    ],
    'messages' => [
        'delete' => 'Notification has been successfully deleted',
        'error' => 'No notifications found',
    ],
];
