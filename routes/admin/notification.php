<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'NotificationController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'NotificationController@getAll',
]);

Route::get('{notification}/view', [
    'as' => '.view',
    'uses' => 'NotificationController@view',
]);

Route::post('read', [
    'as' => '.read',
    'uses' => 'NotificationController@read',
]);

Route::delete('{notification}', [
    'as' => '.delete',
    'uses' => 'NotificationController@delete',
]);
