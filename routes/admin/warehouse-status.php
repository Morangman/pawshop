<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'WarehouseStatusController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'WarehouseStatusController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'WarehouseStatusController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'WarehouseStatusController@store',
]);

Route::get('{warehouse_status}/edit', [
    'as' => '.edit',
    'uses' => 'WarehouseStatusController@edit',
]);

Route::patch('{warehouse_status}', [
    'as' => '.update',
    'uses' => 'WarehouseStatusController@update',
]);

Route::get('{warehouse_status}', [
    'as' => '.get',
    'uses' => 'WarehouseStatusController@get',
]);

Route::delete('{warehouse_status}', [
    'as' => '.delete',
    'uses' => 'WarehouseStatusController@delete',
]);
