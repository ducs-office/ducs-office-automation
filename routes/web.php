<?php

/*
|---4-----------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = Auth::check() ? Auth::user()->name : 'Guest';
    return view('welcome')->with('user',$user);
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->middleware('guest')->name('login');
Route::post('/login', 'Auth\LoginController@login')->middleware('guest');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth');


Route::get('/outgoing-letter-logs/create', 'OutgoingLetterLogsController@create')->middleware('auth');
