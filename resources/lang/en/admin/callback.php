<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Conversations',
        'create' => 'Create Conversation',
        'edit' => 'View Conversation',
    ],
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'text' => 'Text',
    ],
    'index' => [
        'title' => 'Conversations',
        'header_btn' => 'Create Conversation',
        'filters' => [
            'search' => 'Search conversation by typing one of these fields: name',
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
        'title' => 'Create conversation',
    ],
    'edit' => [
        'title' => 'Edit conversation',
    ],
    'delete' => [
        'title' => 'Delete conversation',
    ],
    'messages' => [
        'create' => 'Conversation has been successfully created',
        'update' => 'Conversation has been successfully updated',
        'delete' => 'Conversation has been successfully deleted',
    ],
];
