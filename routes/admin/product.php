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

Route::get('{slug}/edit', [
    'as' => '.edit',
    'uses' => 'ProductController@edit',
]);

Route::patch('{slug}', [
    'as' => '.update',
    'uses' => 'ProductController@update',
]);

Route::post('{slug}/update-price', [
    'as' => '.update-price',
    'uses' => 'ProductController@updatePrice',
]);

Route::post('{slug}/update-premium', [
    'as' => '.update-premium',
    'uses' => 'ProductController@updatePremiumPrice',
]);

Route::post('{slug}/delete-premium', [
    'as' => '.delete-premium',
    'uses' => 'ProductController@deletePremiumPrice',
]);

Route::post('{slug}/generate-prices', [
    'as' => '.generate-prices',
    'uses' => 'ProductController@generatePricesVariations',
]);

Route::get('{slug}', [
    'as' => '.get',
    'uses' => 'ProductController@get',
]);

Route::delete('{slug}', [
    'as' => '.delete',
    'uses' => 'ProductController@delete',
]);
