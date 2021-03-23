<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'StepController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'StepController@getAll',
]);

Route::get('create', [
    'as' => '.create',
    'uses' => 'StepController@create',
]);

Route::post('', [
    'as' => '.store',
    'uses' => 'StepController@store',
]);

Route::get('{stepName}/edit', [
    'as' => '.edit',
    'uses' => 'StepController@edit',
]);

Route::patch('{stepName}', [
    'as' => '.update',
    'uses' => 'StepController@update',
]);

Route::get('{stepName}', [
    'as' => '.get',
    'uses' => 'StepController@get',
]);

Route::delete('{stepName}', [
    'as' => '.delete',
    'uses' => 'StepController@delete',
]);
