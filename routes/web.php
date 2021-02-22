<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::get('/comments', 'HomeController@comments')->name('comments');

Route::get('/cart', 'HomeController@cart')->name('cart');

Route::get('/support', 'HomeController@support')->name('support');

Route::get('/checkout', 'HomeController@checkout')->name('checkout');

Route::get('/account', [
    'middleware' => 'auth',
    'as' => 'account',
    'uses' => 'HomeController@account',
]);

Route::get('/header-search', 'HomeController@headerSearchDevice')->name('header-search');

Route::get('/get-category/{category}', 'HomeController@getByCategory')->name('get-category');

Route::post('/order', 'HomeController@makeOrder')->name('order');

Route::post('/callback', 'HomeController@callback')->name('callback');

Route::patch('/update-account/{user}', [
    'middleware' => 'auth',
    'as' => 'update-account',
    'uses' => 'HomeController@updateAccountInfo',
]);

Route::post('/comment', 'HomeController@addComment')->name('comment');

Route::get('/redirect-google', 'Auth\LoginController@redirectToGoogleProvider')->name('redirect-google');

Route::get('/redirect-facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('redirect-facebook');

Route::get('/callback-google', 'Auth\LoginController@handleProviderGoogleCallback')->name('callback-google');

Route::get('/callback-facebook', 'Auth\LoginController@handleProviderFacebookCallback')->name('callback-facebook');

Route::group([
    'namespace' => 'Auth',
    'as' => 'web',
    'prefix' => 'web',
], static function () {
    require __DIR__.'/web/auth.php';
});
