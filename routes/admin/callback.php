<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'CallbackController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'CallbackController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'CallbackController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'CallbackController@store',
]);

Route::get('{callback}/edit', [
    'as' => '.edit',
    'uses' => 'CallbackController@edit',
]);

Route::patch('{callback}', [
    'as' => '.update',
    'uses' => 'CallbackController@update',
]);

Route::get('{callback}', [
    'as' => '.get',
    'uses' => 'CallbackController@get',
]);

Route::delete('{callback}', [
    'as' => '.delete',
    'uses' => 'CallbackController@delete',
]);
