<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'CategoryController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'CategoryController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'CategoryController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'CategoryController@store',
]);

Route::get('{slug}/edit', [
    'as' => '.edit',
    'uses' => 'CategoryController@edit',
]);

Route::patch('{slug}', [
    'as' => '.update',
    'uses' => 'CategoryController@update',
]);

Route::get('{slug}', [
    'as' => '.get',
    'uses' => 'CategoryController@get',
]);

Route::delete('{slug}', [
    'as' => '.delete',
    'uses' => 'CategoryController@delete',
]);
