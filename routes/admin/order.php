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

Route::get('{order}/barcode', [
    'as' => '.barcode',
    'uses' => 'OrderController@barcode',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'OrderController@create',
]);

Route::get('get-product', [
    'as' => '.get-product',
    'uses' => 'OrderController@getProduct',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'OrderController@store',
]);

Route::post('', [
    'as' => '.search',
    'uses' => 'OrderController@search',
]);

Route::get('{order}/edit', [
    'as' => '.edit',
    'uses' => 'OrderController@edit',
]);

Route::patch('{order}', [
    'as' => '.update',
    'uses' => 'OrderController@update',
]);

Route::patch('{order}/update-order', [
    'as' => '.update-order',
    'uses' => 'OrderController@addOrderedProduct',
]);

Route::patch('{order}/update-order-product', [
    'as' => '.update-order-product',
    'uses' => 'OrderController@updateOrder',
]);

Route::patch('{order}/delete-order-product', [
    'as' => '.delete-order-product',
    'uses' => 'OrderController@deleteOrderProduct',
]);

Route::get('{order}', [
    'as' => '.get',
    'uses' => 'OrderController@get',
]);

Route::delete('{order}', [
    'as' => '.delete',
    'uses' => 'OrderController@delete',
]);
