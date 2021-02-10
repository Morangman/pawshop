<?php

declare(strict_types = 1);

Route::get('login', [
    'as' => '.login.show',
    'uses' => 'LoginController@showLoginForm',
]);

Route::get('logout', [
    'middleware' => 'auth',
    'as' => '.logout',
    'uses' => 'LoginController@logout',
]);

Route::post('login', [
    'as' => '.login.post',
    'uses' => 'LoginController@login',
]);
