<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'StatisticsController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'StatisticsController@getAll',
]);

Route::post('{category}/update', [
    'as' => '.update',
    'uses' => 'StatisticsController@update',
]);