<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'AdminController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'AdminController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'AdminController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'AdminController@store',
]);

Route::get('{user}/edit', [
    'as' => '.edit',
    'uses' => 'AdminController@edit',
]);

Route::patch('{user}', [
    'as' => '.update',
    'uses' => 'AdminController@update',
]);

Route::get('{user}', [
    'as' => '.get',
    'uses' => 'AdminController@get',
]);

Route::delete('{user}', [
    'as' => '.delete',
    'uses' => 'AdminController@delete',
]);
