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

Route::get('/terms', 'HomeController@terms')->name('terms');

Route::get('/user_agreement', 'HomeController@userAgreement')->name('user_agreement');

Route::get('/privacy_policy', 'HomeController@privacyPolicy')->name('privacy_policy');

Route::get('/law_enforcement', 'HomeController@lawEnforcement')->name('law_enforcement');

Route::get('/checkout', 'HomeController@checkout')->name('checkout');

Route::get('/account', [
    'middleware' => 'auth',
    'as' => 'account',
    'uses' => 'HomeController@account',
]);

Route::get('/header-search', 'HomeController@headerSearchDevice')->name('header-search');

Route::get('/get-category/{slug}', 'HomeController@getByCategory')->name('get-category');

Route::get('/order/{order_uuid}/thanks', 'HomeController@thanks')->name('thanks');

Route::get('/order/{order_uuid}/confirm-order', 'HomeController@confirmOrder')->name('confirm-order');

Route::get('/{order}/fedex-label', 'HomeController@getFedexLabel')->name('fedex-label');

Route::get('/redirect-google', 'Auth\LoginController@redirectToGoogleProvider')->name('redirect-google');

Route::get('/redirect-facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('redirect-facebook');

Route::get('/callback-google', 'Auth\LoginController@handleProviderGoogleCallback')->name('callback-google');

Route::get('/callback-facebook', 'Auth\LoginController@handleProviderFacebookCallback')->name('callback-facebook');

Route::post('/callback', 'HomeController@callback')->name('callback');

Route::post('/order', 'HomeController@makeOrder')->name('order');

Route::post('/get-price', 'HomeController@getPrice')->name('get-price');

Route::post('/add-to-box', 'HomeController@addToBox')->name('add-to-box');

Route::post('/comment', 'HomeController@addComment')->name('comment');

Route::patch('/update-account/{user}', [
    'middleware' => 'auth',
    'as' => 'update-account',
    'uses' => 'HomeController@updateAccountInfo',
]);

Route::patch('/update-order/{order}', [
    'middleware' => 'auth',
    'as' => 'update-order',
    'uses' => 'HomeController@updateOrderAddress',
]);

Route::group([
    'namespace' => 'Auth',
    'as' => 'web',
    'prefix' => 'web',
], static function () {
    require __DIR__.'/web/auth.php';
});
