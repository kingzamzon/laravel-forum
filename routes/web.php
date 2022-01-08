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
Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}', 'ThreadController@index')->name('channels.index');

Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('threads.replies');


