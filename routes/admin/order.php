<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'OrderController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'OrderController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'OrderController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'OrderController@store',
]);

Route::get('{order}/edit', [
    'as' => '.edit',
    'uses' => 'OrderController@edit',
]);

Route::patch('{order}', [
    'as' => '.update',
    'uses' => 'OrderController@update',
]);

Route::get('{order}', [
    'as' => '.get',
    'uses' => 'OrderController@get',
]);

Route::delete('{order}', [
    'as' => '.delete',
    'uses' => 'OrderController@delete',
]);
