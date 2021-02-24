<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'TaskController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'TaskController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'TaskController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'TaskController@store',
]);

Route::get('{task}/edit', [
    'as' => '.edit',
    'uses' => 'TaskController@edit',
]);

Route::patch('{task}', [
    'as' => '.update',
    'uses' => 'TaskController@update',
]);

Route::get('{task}', [
    'as' => '.get',
    'uses' => 'TaskController@get',
]);

Route::delete('{task}', [
    'as' => '.delete',
    'uses' => 'TaskController@delete',
]);
