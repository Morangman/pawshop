<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'TipController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'TipController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'TipController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'TipController@store',
]);

Route::get('{tip}/edit', [
    'as' => '.edit',
    'uses' => 'TipController@edit',
]);

Route::patch('{tip}', [
    'as' => '.update',
    'uses' => 'TipController@update',
]);

Route::get('{tip}', [
    'as' => '.get',
    'uses' => 'TipController@get',
]);

Route::delete('{tip}', [
    'as' => '.delete',
    'uses' => 'TipController@delete',
]);
