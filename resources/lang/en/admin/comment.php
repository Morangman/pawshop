<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Comments',
        'create' => 'Create comment',
        'edit' => 'Edit comment',
    ],
    'form' => [
        'name' => 'Name',
        'email' => 'Email',
        'text' => 'Text',
        'value' => 'Rating',
        'is_hidden' => 'Is hidden',
    ],
    'index' => [
        'title' => 'Comments',
        'header_btn' => 'Create comment',
        'filters' => [
            'search' => 'Search comments by typing one of these fields: name',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'text' => 'Text',
                'is_hidden' => 'Is hidden',
                'created_at' => 'Created date',
            ],
        ],
    ],
    'create' => [
        'title' => 'Create comment',
    ],
    'edit' => [
        'title' => 'Edit comment',
    ],
    'delete' => [
        'title' => 'Delete comment',
    ],
    'messages' => [
        'create' => 'Comment has been successfully created',
        'update' => 'Comment has been successfully updated',
        'delete' => 'Comment has been successfully deleted',
    ],
];
