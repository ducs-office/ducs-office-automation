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

Route::prefix('/publications')
->middleware(['auth:web,teachers,scholars'])
->namespace('Publications')
->as('publications.')
->group(static function () {
    Route::get('/journal', 'JournalPublicationController@create')->name('journal.create');
    Route::post('/journal', 'JournalPublicationController@store')->name('journal.store');
    Route::get('/journal/{journal}/edit', 'JournalPublicationController@edit')->name('journal.edit');
    Route::patch('/journal/{journal}', 'JournalPublicationController@update')->name('journal.update');
    Route::delete('/journal/{journal}', 'JournalPublicationController@destroy')->name('journal.destroy');

    Route::get('/conference', 'ConferencePublicationController@create')->name('conference.create');
    Route::post('publications/conference', 'ConferencePublicationController@store')->name('conference.store');
    Route::get('/conference/{conference}/edit', 'ConferencePublicationController@edit')->name('conference.edit');
    Route::patch('/conference/{conference}', 'ConferencePublicationController@update')->name('conference.update');
    Route::delete('/conference/{conference}', 'ConferencePublicationController@destroy')->name('conference.destroy');
});

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

        Route::get(
            '/scholars/{scholar}/leaves/{leave}/attachment',
            'ScholarLeavesController@viewAttachment'
        )->name('scholars.leaves.attachment');

        Route::post(
            '/scholars/{scholar}/advisory-meetings',
            'AdvisoryMeetingsController@store'
        )->name('scholars.advisory_meetings.store');

        Route::get(
            '/advisory-meetings/{meeting}/minutes-of-meeting',
            'AdvisoryMeetingsController@minutesOfMeeting'
        )->name('advisory_meetings.minutes_of_meeting');

        Route::get(
            '/publications',
            'ShowPublications'
        )->name('publications.index');
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

        Route::get('/presentation', 'PresentationController@create')->name('presentation.create');
        Route::post('/presentation', 'PresentationController@store')->name('presentation.store');
        Route::get('/presentation/{presentation}/edit', 'PresentationController@edit')->name('presentation.edit');
        Route::patch('/presentation/{presentation}', 'PresentationController@update')->name('presentation.update');
        Route::delete('/presentation/{presentation}', 'PresentationController@destroy')->name('presentation.destroy');

        Route::post('/leaves', 'LeavesController@store')->name('leaves.store');

        Route::get(
            '/leaves/{leave}/attachment',
            'LeavesController@attachment'
        )->name('leaves.attachment');
    });
