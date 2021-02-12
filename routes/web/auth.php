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

/**
 * Password Reset
 */
Route::get('password/reset', [
    'as' => '.password.request',
    'uses' => 'ForgotPasswordController@showLinkRequestForm',
    'middleware' => ['guest'],
]);

Route::post('password/email', [
    'as' => '.password.email',
    'uses' => 'ForgotPasswordController@sendResetLinkEmail',
    'middleware' => ['guest'],
]);

Route::get('password/reset-success', [
    'as' => '.reset.success',
    'uses' => 'ForgotPasswordController@showSuccessPage',
    'middleware' => ['guest'],
]);

Route::get('password/reset/{token}', [
    'as' => '.password.reset',
    'uses' => 'ResetPasswordController@showResetForm',
    'middleware' => ['guest'],
]);

Route::post('password/reset', [
    'as' => '.password.reset.send',
    'uses' => 'ResetPasswordController@reset',
    'middleware' => ['guest'],
]);

/**
 * Register
 */
Route::get('register', [
    'as' => '.register',
    'uses' => 'RegisterController@showRegistrationForm',
    'middleware' => ['guest'],
]);

Route::get('email/verify/{code}', [
    'as' => '.email.verify',
    'uses' => 'RegisterController@verifyEmail',
    'middleware' => ['guest', 'throttle:3,60'],
]);

Route::post('register', [
    'as' => '.register.store',
    'uses' => 'RegisterController@register',
    'middleware' => ['guest'],
]);
