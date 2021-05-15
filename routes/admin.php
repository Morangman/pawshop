<?php

declare(strict_types = 1);

Route::group(
    [
        'as' => '.user',
        'prefix' => 'user',
        'middleware' => ['role:admin']
    ],
    __DIR__.'/admin/user.php'
);

Route::group(
    [
        'as' => '.admin',
        'prefix' => 'admin',
        'middleware' => ['role:admin']
    ],
    __DIR__.'/admin/admin.php'
);

Route::group(
    [
        'as' => '.category',
        'prefix' => 'category',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/category.php'
);

Route::group(
    [
        'as' => '.product',
        'prefix' => 'product',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/product.php'
);

Route::group(
    [
        'as' => '.statistics',
        'prefix' => 'statistics',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/statistics.php'
);

Route::group(
    [
        'as' => '.daily-statistics',
        'prefix' => 'daily-statistics',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/daily-statistics.php'
);

Route::group(
    [
        'as' => '.faq',
        'prefix' => 'faq',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/faq.php'
);

Route::group(
    [
        'as' => '.task',
        'prefix' => 'task',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/task.php'
);

Route::group(
    [
        'as' => '.step',
        'prefix' => 'step',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/step.php'
);

Route::group(
    [
        'as' => '.step-item',
        'prefix' => 'step-item',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/step-item.php'
);

Route::group(
    [
        'as' => '.tip',
        'prefix' => 'tip',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/tip.php'
);

Route::group(
    [
        'as' => '.order',
        'prefix' => 'order',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/order.php'
);

Route::group(
    [
        'as' => '.warehouse',
        'prefix' => 'warehouse',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/warehouse.php'
);

Route::group(
    [
        'as' => '.order-status',
        'prefix' => 'order-status',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/order-status.php'
);

Route::group(
    [
        'as' => '.comment',
        'prefix' => 'comment',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/comment.php'
);

Route::group(
    [
        'as' => '.callback',
        'prefix' => 'callback',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/callback.php'
);

Route::group(
    [
        'as' => '.notification',
        'prefix' => 'notification',
        'middleware' => ['anyrole:admin|manager']
    ],
    __DIR__.'/admin/notification.php'
);

Route::group(
    [
        'as' => '.setting',
        'prefix' => 'setting',
        'middleware' => ['role:admin']
    ],
    __DIR__.'/admin/setting.php'
);
