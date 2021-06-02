<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'WarehouseController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'WarehouseController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'WarehouseController@create',
]);

Route::post('store', [
    'as' => '.store',
    'uses' => 'WarehouseController@store',
]);

Route::post('import', [
    'as' => '.import',
    'uses' => 'WarehouseController@importXml',
]);

Route::post('', [
    'as' => '.search',
    'uses' => 'WarehouseController@search',
]);

Route::get('{warehouse}/edit', [
    'as' => '.edit',
    'uses' => 'WarehouseController@edit',
]);

Route::patch('{warehouse}', [
    'as' => '.update',
    'uses' => 'WarehouseController@update',
]);

Route::get('{warehouse}', [
    'as' => '.get',
    'uses' => 'WarehouseController@get',
]);

Route::delete('{warehouse}', [
    'as' => '.delete',
    'uses' => 'WarehouseController@delete',
]);

Route::delete('media/{warehouse}/{media}', [
    'as' => '.media.delete',
    'uses' => 'WarehouseController@deleteMedia',
]);
