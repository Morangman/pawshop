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

Route::post('{slug}/update-price', [
    'as' => '.update-price',
    'uses' => 'CategoryController@updatePrice',
]);

Route::post('{slug}/update-premium', [
    'as' => '.update-premium',
    'uses' => 'CategoryController@updatePremiumPrice',
]);

Route::post('{slug}/generate-prices', [
    'as' => '.generate-prices',
    'uses' => 'CategoryController@generatePricesVariations',
]);

Route::get('{slug}', [
    'as' => '.get',
    'uses' => 'CategoryController@get',
]);

Route::delete('{slug}', [
    'as' => '.delete',
    'uses' => 'CategoryController@delete',
]);
