<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'FaqController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'FaqController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'FaqController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'FaqController@store',
]);

Route::get('{faq}/edit', [
    'as' => '.edit',
    'uses' => 'FaqController@edit',
]);

Route::patch('{faq}', [
    'as' => '.update',
    'uses' => 'FaqController@update',
]);

Route::get('{faq}', [
    'as' => '.get',
    'uses' => 'FaqController@get',
]);

Route::delete('{faq}', [
    'as' => '.delete',
    'uses' => 'FaqController@delete',
]);
