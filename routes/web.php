<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Auth\LoginController@showLoginForm')->middleware(['guest', 'guest:teachers'])->name('login_form');
Route::post('/login', 'Auth\LoginController@login')->middleware(['guest', 'guest:teachers'])->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:web,teachers')->name('logout');

Route::get('/teachers/profile', 'TeacherProfileController@index')->middleware('auth:teachers')->name('teachers.dashboard');


