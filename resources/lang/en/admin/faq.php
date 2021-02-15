<?php

declare(strict_types = 1);

return [
    'breadcrumbs' => [
        'index' => 'FAQs',
        'create' => 'Create category',
        'edit' => 'Edit category',
    ],
    'form' => [
        'name' => 'Name*',
        'category' => 'Category for faq',
        'faq_name' => 'Faq name',
        'faq_text' => 'Faq text',
    ],
    'index' => [
        'title' => 'FAQs',
        'header_btn' => 'Create faq',
        'filters' => [
            'search' => 'Search faqs by typing one of these fields: name',
        ],
        'table' => [
            'headers' => [
                'id' => 'ID',
                'name' => 'Name',
                'created_at' => 'Created date',
            ],
        ],
    ],
    'create' => [
        'title' => 'Create faq',
    ],
    'edit' => [
        'title' => 'Edit faq',
    ],
    'delete' => [
        'title' => 'Delete faq',
    ],
    'messages' => [
        'create' => 'FAQ has been successfully created',
        'update' => 'FAQ has been successfully updated',
        'delete' => 'FAQ has been successfully deleted',
    ],
];
