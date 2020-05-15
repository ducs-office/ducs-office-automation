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

//============ scholar documents =====================
Route::get(
    'scholars/{scholar}/document/{document}',
    'ScholarDocumentsController@show'
)->name('scholars.documents.show')->middleware('auth:web,teachers,scholars');

Route::post(
    '/scholars/{scholar}/document',
    'ScholarDocumentsController@store'
)->name('scholars.documents.store')->middleware('auth:web,teachers,scholars');

Route::delete(
    '/scholars/{scholar}/document/{document}',
    'ScholarDocumentsController@destroy'
)->name('scholars.documents.destroy')->middleware('auth:web,teachers,scholars');

//=========== scholar pre-phd seminar =============

Route::get(
    '/scholars/{scholar}/pre-phd-seminar/',
    'ScholarAppealController@showPhdSeminarForm'
)->name('scholars.pre_phd_seminar.show')->middleware('auth:web,teachers,scholars');

Route::post(
    '/scholars/{scholar}/pre-phd-seminar/apply/',
    'ScholarAppealController@storePhdSeminar'
)->name('scholars.pre_phd_seminar.apply')->middleware('auth:scholars');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/recommend',
    'ScholarAppealController@recommend'
)->name('scholars.appeals.recommend')->middleware('auth:web,teachers');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/respond',
    'ScholarAppealController@respond'
)->name('scholars.appeals.respond')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/approve',
    'ScholarAppealController@approve'
)->name('scholars.appeals.approve')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/reject',
    'ScholarAppealController@reject'
)->name('scholars.appeals.reject')->middleware('auth:web');

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
    Route::post('/conference', 'ConferencePublicationController@store')->name('conference.store');
    Route::get('/conference/{conference}/edit', 'ConferencePublicationController@edit')->name('conference.edit');
    Route::patch('/conference/{conference}', 'ConferencePublicationController@update')->name('conference.update');
    Route::delete('/conference/{conference}', 'ConferencePublicationController@destroy')->name('conference.destroy');

    Route::get('/co-authors/{coAuthor}', 'CoAuthorController@show')->name('co_authors.show');
    Route::delete('/co-authors/{coAuthor}', 'CoAuthorController@destroy')->name('co_authors.destroy');
});

Route::post(
    '/scholars/@{scholar}/progress-report',
    'ScholarProgressReportController@store'
)->name('scholars.progress_reports.store')
->middleware('auth:web');

Route::get(
    'scholars/@{scholar}/progress-report/{report}',
    'ScholarProgressReportController@show'
)->name('scholars.progress_reports.show')
->middleware(['auth:web,teachers,scholars']);

Route::delete(
    'scholars/@{scholar}/progress-report/{report}',
    'ScholarProgressReportController@destroy'
)->name('scholars.progress_reports.destroy')
->middleware('auth:web');

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
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('/profile', 'ProfileController@index')->name('profile');
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
    });
