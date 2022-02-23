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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// Route::resource('threads', 'ThreadController');
Route::get('/threads', 'ThreadController@index')->name('threads.index');
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.destory');
Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}', 'ThreadController@index')->name('channels.index');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('threads.replies');
Route::patch('/replies/{reply}', 'ReplyController@update')->name('replies.update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.delete');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store');

Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy');

Route::post('/replies/{reply}/favourites', 'FavouriteController@store')->name('favourites.replies');
Route::delete('/replies/{reply}/favourites', 'FavouriteController@destroy')->name('favourites.delete');

Route::get('/profile/{user}', 'ProfileController@show')->name('user.profile');

Route::get('/profile/{user}/notifications', 'UserNotificationsController@index')
        ->name('user.notifications');

Route::delete('/profile/{user}/notifications/{notification}', 'UserNotificationsController@destroy')
        ->name('user.notification.clear');
