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

Route::get('{category}/edit', [
    'as' => '.edit',
    'uses' => 'ProductController@edit',
]);

Route::patch('{category}', [
    'as' => '.update',
    'uses' => 'ProductController@update',
]);

Route::post('{category}/update-price', [
    'as' => '.update-price',
    'uses' => 'ProductController@updatePrice',
]);

Route::post('{category}/update-premium', [
    'as' => '.update-premium',
    'uses' => 'ProductController@updatePremiumPrice',
]);

Route::post('{category}/delete-premium', [
    'as' => '.delete-premium',
    'uses' => 'ProductController@deletePremiumPrice',
]);

Route::post('{category}/generate-prices', [
    'as' => '.generate-prices',
    'uses' => 'ProductController@generatePricesVariations',
]);

Route::get('{category}', [
    'as' => '.get',
    'uses' => 'ProductController@get',
]);

Route::delete('{category}', [
    'as' => '.delete',
    'uses' => 'ProductController@delete',
]);
