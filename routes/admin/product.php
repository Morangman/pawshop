<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'ProductController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'ProductController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'ProductController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'ProductController@store',
]);

Route::get('{product}/edit', [
    'as' => '.edit',
    'uses' => 'ProductController@edit',
]);

Route::patch('{product}', [
    'as' => '.update',
    'uses' => 'ProductController@update',
]);

Route::delete('{product}', [
    'as' => '.delete',
    'uses' => 'ProductController@delete',
]);

Route::delete('media/{product}/{media}', [
    'as' => '.media.delete',
    'uses' => 'ProductController@deleteMedia',
]);
