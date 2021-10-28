<?php

declare(strict_types = 1);

Route::get('/', [
    'as' => '.index',
    'uses' => 'AllDailyStatisticsController@index',
]);

Route::get('all', [
    'as' => '.all',
    'uses' => 'AllDailyStatisticsController@getAll',
]);

Route::post('{category}/update', [
    'as' => '.update',
    'uses' => 'AllDailyStatisticsController@update',
]);