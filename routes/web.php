<?php

use Illuminate\Support\Facades\Route;

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
)->name('scholars.documents.show')->middleware('auth:web,scholars');

Route::post(
    '/scholars/{scholar}/document',
    'ScholarDocumentsController@store'
)->name('scholars.documents.store')->middleware('auth:web,scholars');

Route::delete(
    '/scholars/{scholar}/document/{document}',
    'ScholarDocumentsController@destroy'
)->name('scholars.documents.destroy')->middleware('auth:web,scholars');

//=========== scholar pre-phd seminar =============
Route::get(
    '/scholars/{scholar}/pre-phd-seminar/request',
    'ScholarPrePhdSeminarController@request'
)->name('scholars.pre_phd_seminar.request')->middleware('auth:scholars');

Route::post(
    '/scholars/{scholar}/pre-phd-seminar/apply',
    'ScholarPrePhdSeminarController@apply'
)->name('scholars.pre_phd_seminar.apply')->middleware('auth:scholars');

Route::get(
    '/scholars/{scholar}/pre-phd-seminar/{prePhdSeminar}/show',
    'ScholarPrePhdSeminarController@show'
)->name('scholars.pre_phd_seminar.show')->middleware('auth:web,scholars');

Route::patch(
    '/scholars/{scholar}/appeals/{prePhdSeminar}/forward',
    'ScholarPrePhdSeminarController@forward'
)->name('scholars.pre_phd_seminar.forward')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{prePhdSeminar}/schedule',
    'ScholarPrePhdSeminarController@schedule'
)->name('scholars.pre_phd_seminar.schedule')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/appeals/{prePhdSeminar}/finalize',
    'ScholarPrePhdSeminarController@finalize'
)->name('scholars.pre_phd_seminar.finalize')->middleware('auth:web');

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
    '/scholars/{scholar}/title-approval/{titleApproval}/show',
    'ScholarTitleApprovalController@show'
)->name('scholars.title_approval.show')->middleware('auth:web,scholars');

Route::patch(
    '/scholars/{scholar}/title-approval/{titleApproval}/recommend',
    'ScholarTitleApprovalController@recommend'
)->name('scholars.title_approval.recommend')->middleware('auth:web');

Route::patch(
    '/scholars/{scholar}/title-approval/{titleApproval}/approve',
    'ScholarTitleApprovalController@approve'
)->name('scholars.title_approval.approve')->middleware('auth:web');

Route::get('/users/@{user}', 'UserProfileController@show')->name('profiles.show');
Route::patch('/users/@{user}', 'UserProfileController@update')->name('profiles.update');
Route::get('/users/@{user}/avatar', 'UserProfileController@avatar')->name('profiles.avatar');

Route::get('/teaching-records', 'TeachingRecordsController@index')->name('teaching-records.index');
Route::post('/teaching-details/send', 'TeachingRecordsController@store')->name('teaching-details.send');
Route::get('/teaching-records/export', 'TeachingRecordsController@export')->name('teaching-records.export');
Route::post('/teaching-records/start', 'TeachingRecordsController@start')->name('teaching-records.start');
Route::patch('/teaching-records/extend', 'TeachingRecordsController@extend')->name('teaching-records.extend');

//===================Scholar Examiner==============
Route::post('scholars/{scholar}/examiner/apply', 'ScholarExaminerController@apply')
    ->middleware('auth:web')
    ->name('scholars.examiner.apply');

Route::patch('scholars/{scholar}/examiner/{examiner}/recommend', 'ScholarExaminerController@recommend')
    ->middleware('auth:web')
    ->name('scholars.examiner.recommend');

Route::patch('scholars/{scholar}/examiner/{examiner}/approve', 'ScholarExaminerController@approve')
    ->middleware('auth:web')
    ->name('scholars.examiner.approve');

Route::prefix('/publications')
->middleware('auth:web,scholars')
->namespace('Publications')
->as('publications.')
->group(static function () {
    Route::get('/', 'PublicationController@create')->name('create');
    Route::get('/{publication}', 'PublicationController@show')->name('show');
    Route::post('/', 'PublicationController@store')->name('store');
    Route::get('/{publication}/edit', 'PublicationController@edit')->name('edit');
    Route::patch('/{publication}', 'PublicationController@update')->name('update');
    Route::delete('/{publication}', 'PublicationController@destroy')->name('destroy');

    Route::get('{publication}/co-authors/{coAuthor}', 'CoAuthorController@show')->name('co_authors.show');
    Route::post('/{publication}/co-authors', 'CoAuthorController@store')->name('co_authors.store');
    Route::patch('/{publication}/co-authors/{coAuthor}', 'CoAuthorController@update')->name('co_authors.update');
    Route::delete('/{publication}/co-authors/{coAuthor}', 'CoAuthorController@destroy')->name('co_authors.destroy');
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
->middleware(['auth:web,scholars']);

Route::delete(
    'scholars/@{scholar}/progress-report/{report}',
    'ScholarProgressReportController@destroy'
)->name('scholars.progress_reports.destroy')
->middleware('auth:web');

Route::get('/scholars/@{scholar}', 'Scholars\ProfileController@show')->name('scholars.profile.show')->middleware('auth:web,scholars');
Route::patch('/scholars/@{scholar}', 'Scholars\ProfileController@update')->name('scholars.profile.update')->middleware('auth:web,scholars');
Route::get('/scholars/@{scholar}/avatar', 'Scholars\ProfileController@avatar')->name('scholars.profile.avatar')->middleware('auth:web,scholars');

Route::prefix('/research')
    ->middleware(['auth:web'])
    ->namespace('Research')
    ->as('research.')
    ->group(static function () {
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
            'ScholarController@replaceAdvisors'
        )->name('scholars.advisors.replace');
    });

Route::prefix('/scholars')
    ->middleware('auth:scholars')
    ->namespace('Scholars')
    ->as('scholars.')
    ->group(static function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');

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
