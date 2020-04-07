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

Route::prefix('/research')
    ->middleware(['auth:web,teachers'])
    ->namespace('Research')
    ->as('research.')
    ->group(static function () {
        Route::get('/scholars', 'ScholarController@index')->name('scholars.index');
        Route::get('/scholars/{scholar}', 'ScholarController@show')->name('scholars.show');

        Route::post(
            '/scholars/{scholar}/coursework',
            'ScholarCourseworkController@store'
        )->name('scholars.courseworks.store');

        Route::patch(
            '/scholars/{scholar}/coursework/{courseId}',
            'ScholarCourseworkController@complete'
        )->name('scholars.courseworks.complete');

        Route::patch(
            '/scholars/{scholar}/leaves/{leave}/recommend',
            'ScholarLeavesController@recommend'
        )->name('scholars.leaves.recommend');

        Route::patch(
            '/scholars/{scholar}/leaves/{leave}/approve',
            'ScholarLeavesController@approve'
        )->name('scholars.leaves.approve');

        Route::patch(
            '/scholars/{scholar}/leaves/{leave}/reject',
            'ScholarLeavesController@reject'
        )->name('scholars.leaves.reject');

        Route::post(
            '/scholars/{scholar}/advisory-meetings',
            'AdvisoryMeetingsController@store'
        )->name('scholars.advisory_meetings.store');

        Route::get(
            '/advisory-meetings/{meeting}/minutes-of-meeting',
            'AdvisoryMeetingsController@minutesOfMeeting'
        )->name('advisory_meetings.minutes_of_meeting');
    });

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

        Route::get('/profile/publication', 'PublicationController@create')->name('profile.publication.create');
        Route::post('/profile/publication', 'PublicationController@store')->name('profile.publication.store');
        Route::get('profile/publication/{publication}/edit', 'PublicationController@edit')->name('profile.publication.edit');
        Route::patch('profile/publication/{publication}', 'PublicationController@update')->name('profile.publication.update');
        Route::delete('/profile/publication/{publication}', 'PublicationController@destroy')->name('profile.publication.destroy');

        Route::post('/profile/presentation', 'PresentationController@store')->name('profile.presentation.store');
        Route::patch('profile/presentation/{presentation}', 'PresentationController@update')->name('profile.presentation.update');
        Route::delete('/profile/presentation/{presentation}', 'PresentationController@destroy')->name('profile.presentation.destroy');

        Route::post(
            '/leaves',
            'LeavesController@store'
        )->name('leaves.store');
    });
