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

Route::get('/guarantee', 'HomeController@guarantee')->name('guarantee');

Route::get('/accessories', 'HomeController@accessories')->name('accessories');

Route::post('/order', 'HomeController@makeOrder')->name('order');

Route::post('/call', 'HomeController@callMe')->name('call');

Route::post('/comment', 'HomeController@addComment')->name('comment');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');

Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::group([
    'namespace' => 'Auth',
    'as' => 'web',
    'prefix' => 'web',
], static function () {
    require __DIR__.'/web/auth.php';
});
