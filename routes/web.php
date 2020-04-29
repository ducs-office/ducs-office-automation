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

        Route::get(
            '/scholars/{scholar}/courseworks/{course}/marksheet',
            'ScholarCourseworkController@viewMarksheet'
        )->name('scholars.courseworks.marksheet');

        Route::patch(
            '/scholars/{scholar}/leaves/{leave}/recommend',
            'ScholarLeavesController@recommend'
        )->name('scholars.leaves.recommend');

        Route::patch(
            '/scholars/{scholar}/leaves/{leave}/respond',
            'ScholarLeavesController@respond'
        )->name('scholars.leaves.respond');

        Route::get(
            '/scholars/{scholar}/leaves/{leave}/response-letter',
            'ScholarLeavesController@viewResponseLetter'
        )->name('scholars.leaves.response_letter');

        Route::get(
            '/scholars/{scholar}/leaves/{leave}/application',
            'ScholarLeavesController@viewApplication'
        )->name('scholars.leaves.application');

        Route::post(
            '/scholars/{scholar}/advisory-meetings',
            'ScholarAdvisoryMeetingsController@store'
        )->name('scholars.advisory_meetings.store');

        Route::get(
            '/advisory-meetings/{meeting}/minutes-of-meeting',
            'ScholarAdvisoryMeetingsController@minutesOfMeeting'
        )->name('scholars.advisory_meetings.minutes_of_meeting');

        Route::get(
            '/publications',
            'ShowPublications'
        )->name('publications.index');

        Route::patch(
            '/scholars/{scholar}/advisory-committee',
            'ScholarController@updateAdvisoryCommittee'
        )->name('scholars.advisory_committee.update');

        Route::patch(
            '/scholars/{scholar}/replace-advisory-committee',
            'ScholarController@replaceAdvisoryCommittee'
        )->name('scholars.advisory_committee.replace');

        Route::post(
            '/scholars/{scholar}/progress-report',
            'ScholarProgressReportsController@store'
        )->name('scholars.progress_reports.store');

        Route::get(
            'scholars/{scholar}/progress-report/{document}/attachment',
            'ScholarProgressReportsController@viewAttachment'
        )->name('scholars.progress_reports.attachment');

        Route::post(
            '/scholars/{scholar}/document',
            'ScholarOtherDocumentsController@store'
        )->name('scholars.documents.store');

        Route::get(
            'scholars/{scholar}/document/{document}/attachment',
            'ScholarOtherDocumentsController@viewAttachment'
        )->name('scholars.documents.attachment');
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
            '/leaves/{leave}/application',
            'LeavesController@viewApplication'
        )->name('leaves.application');

        Route::get(
            '/leaves/{leave}/response-letter',
            'LeavesController@viewResponseLetter'
        )->name('leaves.response_letter');

        Route::get(
            '/courseworks/{course}/marksheet',
            'CourseworkController@viewMarksheet'
        )->name('courseworks.marksheet');

        Route::get(
            '/advisory-meetings/{meeting}/minutes-of-meeting',
            'AdvisoryMeetingsController@minutesOfMeeting'
        )->name('advisory_meetings.minutes_of_meeting');

        Route::get(
            '/progress-reports/{document}/attachment',
            'ProgressReportsController'
        )->name('progress_reports.attachment');

        Route::get(
            '/document/{document}/attachment',
            'OtherDocumentsController'
        )->name('documents.attachment');
    });
