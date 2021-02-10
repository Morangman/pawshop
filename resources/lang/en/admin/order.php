<?php

declare(strict_types = 1);

use App\Order;

return [
    'breadcrumbs' => [
        'index' => 'Заявки',
        'create' => 'Створити заявку',
        'edit' => 'Редагувати заявку',
    ],
    'form' => [
        'name' => 'Ім\'я',
        'phone' => 'Телефон',
        'email' => 'Email',
        'notes' => 'Примітки до заявки',
        'ordered_product' => 'Замовлені продукти',
        'ordered_status' => 'Статус заявки',
        'created_at' => 'Дата створення',
        'updated_at' => 'Дата оновлення',
        'summ' => 'Сумма замовлених товарів',
        'order_number' => 'Номер заявки',
        'add_suspect_ip' => 'Додати IP адресу в підозрілі',
        'suspect_ip' => 'IP адреса позначена як підозріла',
        'ordered_products' => [
            'color' => 'Колір',
            'quantity' => 'Кількість',
            'price' => 'Ціна',
            'unit' => 'грн.',
        ],
        'reminder' => [
            'name' => 'Створити нагадування',
            'title' => 'Назва задачі',
            'text' => 'Коментар',
            'date' => 'Дата',
        ],
    ],
    'index' => [
        'title' => 'Заявки',
        'header_btn' => 'Створити заявку',
        'filters' => [
            'search' => 'Пошук заявки, ввівши одне з цих полів: ім’я',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Ім\'я',
                'contacts' => 'Контакти',
                'status' => 'Статус',
                'created_at' => 'Дата створення',
            ],
        ],
        'search' => [
            'all' => 'Всі',
        ],
    ],
    'order_statuses' => [
        Order::STATUS_NEW => 'Нове замовлення',
        Order::STATUS_IN_PROCESS => 'В роботі',
        Order::STATUS_PAYMENT_EXPECTED => 'Очікується оплата',
        Order::STATUS_COMPLETED => 'Успішно виконано',
        Order::STATUS_POSTPONED => 'Рішення відкладено',
    ],
    'create' => [
        'title' => 'Створити заявку',
    ],
    'edit' => [
        'title' => 'Редагувати заявку',
    ],
    'delete' => [
        'title' => 'Видалити заявку',
    ],
    'reminder' => [
        'title' => 'Нагадування з сайту',
        'text' => 'Нагадування з сайту',
    ],
    'messages' => [
        'update' => 'Заявку успішно оновлено',
        'create' => 'Заявку успішно створено',
        'delete' => 'Заявку успішно видалено',
        'suspect_ip' => 'Ip успішно додано до підозрілих',
    ],
];
