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

Route::get('/', 'Auth\LoginController@showLoginForm')->middleware(['guest', 'guest:teachers', 'guest:scholars'])->name('login_form');
Route::post('/login', 'Auth\LoginController@login')->middleware(['guest', 'guest:teachers', 'guest:scholars'])->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:web,teachers,scholars')->name('logout');

Route::prefix('/teachers')
    ->middleware('auth:teachers')
    ->namespace('Teachers')
    ->as('teachers.')
    ->group(static function () {
        Route::get('/', 'ProfileController@index')->name('profile');
        Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
        Route::patch('/profile', 'ProfileController@update')->name('profile.update');
        Route::get('/profile/avatar', 'ProfileController@avatar')->name('profile.avatar');
        Route::post('/profile/submit', 'TeachingRecordsController@store')->name('profile.submit');
        Route::get('/scholars', 'ScholarsController@index')->name('scholars.index');
    });

Route::prefix('/scholars')
    ->middleware('auth:scholars')
    ->namespace('Scholars')
    ->as('scholars.')
    ->group(static function () {
        Route::get('/', 'ProfileController@index')->name('profile');
        Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
        Route::patch('/profile', 'ProfileController@update')->name('profile.update');
        Route::get('/profile/avatar', 'ProfileController@avatar')->name('profile.avatar');
    });
