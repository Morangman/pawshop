<?php

declare(strict_types = 1);

return [
    'models' => [
        'user' => App\User::class,
    ],

    'tables' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'users' => 'users',

        // Pivot tables.
        'role_user' => 'role_user',
        'permission_role' => 'permission_role',
        'permission_user' => 'permission_user',
    ],

    'roles_permissions' => [
        'manager' => [
            //routes
        ],
        'admin' => [
            //routes
        ],
    ],
];
