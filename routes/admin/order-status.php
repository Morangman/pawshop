<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'OrderStatusController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'OrderStatusController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'OrderStatusController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'OrderStatusController@store',
]);

Route::get('{status}/edit', [
    'as' => '.edit',
    'uses' => 'OrderStatusController@edit',
]);

Route::patch('{status}', [
    'as' => '.update',
    'uses' => 'OrderStatusController@update',
]);

Route::get('{status}', [
    'as' => '.get',
    'uses' => 'OrderStatusController@get',
]);

Route::delete('{status}', [
    'as' => '.delete',
    'uses' => 'OrderStatusController@delete',
]);
