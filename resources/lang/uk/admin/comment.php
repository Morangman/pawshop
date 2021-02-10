<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'Відгуки',
        'create' => 'Створити відгук',
        'edit' => 'Редагувати відгук',
    ],
    'form' => [
        'name' => 'Ім\'я',
        'email' => 'Email',
        'text' => 'Текст',
        'value' => 'Оцінка',
        'is_hidden' => 'Приховано',
    ],
    'index' => [
        'title' => 'Відгуки',
        'header_btn' => 'Створити відгук',
        'filters' => [
            'search' => 'Пошук відгуків, ввівши одне з цих полів: ім’я',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Ім\'я',
                'text' => 'Текст',
                'is_hidden' => 'Приховано',
                'created_at' => 'Дата створення',
            ],
        ],
    ],
    'create' => [
        'title' => 'Створити відгук',
    ],
    'edit' => [
        'title' => 'Редагувати відгук',
    ],
    'delete' => [
        'title' => 'Видалити відгук',
    ],
    'messages' => [
        'update' => 'Відгук успішно оновлено',
        'create' => 'Відгук успішно створено',
        'delete' => 'Відгук успішно видалено',
    ],
];
