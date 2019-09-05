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

Route::get('/login', 'LoginController@showLoginForm');
Route::post('/login', 'LoginController@login');
Route::get('/logout', 'LogOutController@logout');
// Route::get('/logout', 'Auth\LoginController@logout');
// ->middleware('guest')
