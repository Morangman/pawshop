<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Config;

return [
    'breadcrumbs' => [
        'index' => 'Користувачі',
        'create' => 'Створити користувача',
        'edit' => 'Редагувати користувача',
    ],
    'form' => [
        'name' => 'Ім\'я',
        'phone' => 'Номер телефону',
        'email' => 'Email',
        'role' => 'Роль',
        'password' => 'Пароль',
        'password_confirmation' => 'Підтвердьте пароль',
    ],
    'index' => [
        'title' => 'Користувачі',
        'header_btn' => 'Створити користувача',
        'filters' => [
            'search' => 'Пошук користувачів, ввівши одне з цих полів: ім’я чи електронну пошту',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Ім\'я',
                'phone' => 'Номер телефону',
                'email' => 'Email',
                'role' => 'Роль',
                'created_at' => 'Дата реєстрації',
            ],
        ],
    ],
    'roles' => Config::get('roles.roles_permissions', []),
    'create' => [
        'title' => 'Створити користувача',
    ],
    'edit' => [
        'title' => 'Редагувати користувача',
    ],
    'delete' => [
        'title' => 'Видалити користувача',
    ],
    'messages' => [
        'update' => 'Користувача успішно оновлено',
        'create' => 'Користувача успішно створено',
        'delete' => 'Користувача успішно видалено',
    ],
];
