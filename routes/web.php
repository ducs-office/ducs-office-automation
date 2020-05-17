<?php

use App\Mail\UserRegisteredMail;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|---------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm')->middleware(['guest', 'guest:scholars'])->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->middleware(['guest', 'guest:scholars'])->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:web,scholars')->name('logout');
Route::get('/forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->middleware(['guest', 'guest:scholars'])->name('password.forgot');
Route::post('/forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware(['guest', 'guest:scholars'])->name('password.send');
Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->middleware(['guest', 'guest:scholars'])->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->middleware(['guest', 'guest:scholars'])->name('password.update');

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
    '/scholars/{scholar}/appeals/{appeal}/approve',
    'ScholarAppealController@approve'
)->name('scholars.appeals.approve')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/reject',
    'ScholarAppealController@reject'
)->name('scholars.appeals.reject')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{appeal}/mark-complete',
    'ScholarAppealController@markComplete'
)->name('scholars.appeals.mark_complete')->middleware('auth:web,teachers');

//================Scholar Title Approval =======================

Route::get(
    '/scholars/{scholar}/title-approval/request',
    'ScholarTitleApprovalController@request'
)->name('scholars.title_approval.request')->middleware('auth:scholars');

Route::post(
    '/scholars/{scholar}/title-approval/apply',
    'ScholarTitleApprovalController@apply'
)->name('scholars.title_approval.apply')->middleware('auth:scholars');

Route::get(
    '/scholars/{scholar}/title-approval/show',
    'ScholarTitleApprovalController@show'
)->name('scholars.title_approval.show')->middleware('auth:web,teachers,scholars');

Route::patch(
    '/scholars/{scholar}/title-approval/{appeal}/approve',
    'ScholarTitleApprovalController@approve'
)->name('scholars.title_approval.approve')->middleware('auth:web,scholars');

Route::patch(
    '/scholars/{scholar}/title-approval/{appeal}/mark-complete',
    'ScholarTitleApprovalController@markComplete'
)->name('scholars.title_approval.mark_complete')->middleware('auth:web,teachers');

Route::get('/users/@{user}', 'UserProfileController@show')->name('profiles.show');
Route::patch('/users/@{user}', 'UserProfileController@update')->name('profiles.update');
Route::get('/users/@{user}/avatar', 'UserProfileController@avatar')->name('profiles.avatar');

Route::get('/teaching-records', 'TeachingRecordsController@index')->name('teaching-records.index');
Route::post('/teaching-details/send', 'TeachingRecordsController@store')->name('teaching-details.send');
Route::get('/teaching-records/export', 'TeachingRecordsController@export')->name('teaching-records.export');
Route::post('/teaching-records/start', 'TeachingRecordsController@start')->name('teaching-records.start');
Route::patch('/teaching-records/extend', 'TeachingRecordsController@extend')->name('teaching-records.extend');

Route::prefix('/publications')
->middleware(['auth:web,scholars'])
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
    ->middleware(['auth:web'])
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
            '/scholars/{scholar}/advisors',
            'ScholarController@updateAdvisors'
        )->name('scholars.advisors.update');
        Route::patch(
            '/scholars/{scholar}/advisors',
            'ScholarController@updateAdvisors'
        )->name('scholars.advisors.update');

        Route::patch(
            '/scholars/{scholar}/advisors/replace',
            'ScholarController@replaceAdvisoryCommittee'
        )->name('scholars.advisors.replace');
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

        Route::patch(
            'scholars/{scholar}/propposed-title/update',
            'ProposedTitleController@update',
        )->name('proposed_title.update');
    });

Route::get('/external-authorities', 'ExternalAuthorityController@index')->name('external-authority.index');
Route::post('/external-authorities', 'ExternalAuthorityController@store')->name('external-authority.store');
Route::patch('/external-authorities/{externalAuthority}', 'ExternalAuthorityController@update')->name('external-authority.update');
