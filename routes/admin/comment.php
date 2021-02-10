<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'CommentController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'CommentController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'CommentController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'CommentController@store',
]);

Route::get('{comment}/edit', [
    'as' => '.edit',
    'uses' => 'CommentController@edit',
]);

Route::patch('{comment}', [
    'as' => '.update',
    'uses' => 'CommentController@update',
]);

Route::get('{comment}', [
    'as' => '.get',
    'uses' => 'CommentController@get',
]);

Route::delete('{comment}', [
    'as' => '.delete',
    'uses' => 'CommentController@delete',
]);
