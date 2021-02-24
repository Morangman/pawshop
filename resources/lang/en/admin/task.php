<?php

declare(strict_types = 1);

use App\Task;

return [
    'breadcrumbs' => [
        'index' => 'Tasks',
        'create' => 'Create task',
        'edit' => 'Edit task',
    ],
    'form' => [
        'name' => 'Name*',
        'text' => 'Description of the task*',
        'notes' => 'Notes',
        'videos' => 'Videos',
        'task_status' => 'Status',
    ],
    'index' => [
        'title' => 'Tasks',
        'header_btn' => 'Create task',
        'filters' => [
            'search' => 'Search task by typing one of these fields: name',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'status' => 'Status',
                'created_at' => 'Created date',
            ],
        ],
        'search' => [
            'all' => 'All',
        ],
    ],
    'task_statuses' => [
        Task::STATUS_NEW => 'New task',
        Task::STATUS_IN_PROCESS => 'In work',
        Task::STATUS_COMPLETED => 'Successfully completed',
        Task::STATUS_POSTPONED => 'The decision is postponed',
    ],
    'create' => [
        'title' => 'Create task',
    ],
    'edit' => [
        'title' => 'Edit task',
    ],
    'delete' => [
        'title' => 'Delete task',
    ],
    'messages' => [
        'create' => 'Task has been successfully created',
        'update' => 'Task has been successfully updated',
        'delete' => 'Task has been successfully deleted',
    ],
];
