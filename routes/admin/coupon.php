<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'CouponController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'CouponController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'CouponController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'CouponController@store',
]);

Route::get('{coupon}/edit', [
    'as' => '.edit',
    'uses' => 'CouponController@edit',
]);

Route::patch('{coupon}', [
    'as' => '.update',
    'uses' => 'CouponController@update',
]);

Route::get('{coupon}', [
    'as' => '.get',
    'uses' => 'CouponController@get',
]);

Route::delete('{coupon}', [
    'as' => '.delete',
    'uses' => 'CouponController@delete',
]);
