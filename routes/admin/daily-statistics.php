<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'DailyStatisticsController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'DailyStatisticsController@getAll',
]);

Route::post('{category}/update', [
    'as' => '.update',
    'uses' => 'DailyStatisticsController@update',
]);