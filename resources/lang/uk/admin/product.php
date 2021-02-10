<?php

declare(strict_types = 1);

use App\Product;

return [
    'breadcrumbs' => [
        'index' => 'Товари',
        'create' => 'Створити товар',
        'edit' => 'Редагувати товар',
    ],
    'form' => [
        'category' => 'Категорія',
        'title' => 'Заголовок',
        'general' => 'Основні дані',
        'general_info' => 'Опис продукту',
        'price' => 'Ціна',
        'old_price' => 'Стара ціна',
        'image' => 'Фото',
        'variations' => 'Кольори',
        'recommended_products' => 'Рекомендовані товари',
        'seo' => 'SEO налаштування',
        'status' => 'Статус',
        'hidden' => 'Приховано',
        'variations_form' => [
            'color_name' => 'Назва кольору',
            'color' => 'Колір',
            'price' => 'Ціна',
            'old_price' => 'Стара ціна',
            'image' => 'Фото відносно кольору',
        ],
        'seo_form' => [
            'title' => 'Заголовок',
            'meta' => 'Мета опис',
            'keywords' => 'Ключові слова',
            'image' => 'Фото',
        ],
    ],
    'index' => [
        'title' => 'Товари',
        'header_btn' => 'Створити товар',
        'filters' => [
            'search' => 'Пошук товару, ввівши одне з цих полів: Заголовок',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'title' => 'Заголовок',
                'status' => 'Статус',
                'created_at' => 'Дата створення',
            ],
        ],
    ],
    'product_status' => [
        Product::STATUS_ACTIVE => 'Опубліковано',
        Product::STATUS_HIDDEN => 'Приховано',
    ],
    'create' => [
        'title' => 'Створити товар',
    ],
    'edit' => [
        'title' => 'Редагувати товар',
    ],
    'delete' => [
        'title' => 'Видалити товар',
    ],
    'messages' => [
        'update' => 'Товар успішно оновлено',
        'create' => 'Товар успішно створено',
        'delete' => 'Товар успішно видалено',
        'seo_image_delete' => 'Фото успішно видалено',
    ],
];
