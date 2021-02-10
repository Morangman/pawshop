<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'UserController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'UserController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'UserController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'UserController@store',
]);

Route::get('{user}/edit', [
    'as' => '.edit',
    'uses' => 'UserController@edit',
]);

Route::patch('{user}', [
    'as' => '.update',
    'uses' => 'UserController@update',
]);

Route::get('{user}', [
    'as' => '.get',
    'uses' => 'UserController@get',
]);

Route::delete('{user}', [
    'as' => '.delete',
    'uses' => 'UserController@delete',
]);
