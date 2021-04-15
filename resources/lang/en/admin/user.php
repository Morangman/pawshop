<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Config;

return [
    'breadcrumbs' => [
        'index' => 'Users',
        'create' => 'Create user',
        'edit' => 'Edit user',
    ],
    'form' => [
        'name' => 'Name',
        'phone' => 'Phone number',
        'email' => 'Email',
        'role' => 'Role',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
    ],
    'index' => [
        'title' => 'Users',
        'header_btn' => 'Create user',
        'filters' => [
            'search' => 'Search users by typing one of these fields: name, email',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'phone' => 'Phone number',
                'email' => 'Email',
                'role' => 'Role',
                'created_at' => 'Created date',
                'last_accessed_at' => 'Online at',
            ],
        ],
    ],
    'roles' => Config::get('roles.roles_permissions', []),
    'create' => [
        'title' => 'Create user',
    ],
    'edit' => [
        'title' => 'Edit user',
    ],
    'delete' => [
        'title' => 'Delete user',
    ],
    'messages' => [
        'create' => 'User has been successfully created',
        'update' => 'User has been successfully updated',
        'delete' => 'User has been successfully deleted',
    ],
];
