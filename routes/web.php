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

Route::view('/team', 'team')->name('team');

Route::get('/', 'Auth\LoginController@showLoginForm')->middleware(['guest', 'guest:scholars'])->name('login-form');
Route::post('/login', 'Auth\LoginController@login')->middleware(['guest', 'guest:scholars'])->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth:web,scholars')->name('logout');
Route::get('/forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->middleware(['guest', 'guest:scholars'])->name('password.forgot');
Route::post('/forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware(['guest', 'guest:scholars'])->name('password.send');
Route::get('/password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm')->middleware(['guest', 'guest:scholars'])->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->middleware(['guest', 'guest:scholars'])->name('password.update');

//============ scholar leaves ====================
Route::get(
    '/scholars/@{scholar}/leaves',
    'LeavesController@index'
)->name('scholars.leaves.index')->middleware('auth:web,scholars');

Route::get(
    '/scholars/@{scholar}/leaves/{leave}/response-letter',
    'LeavesController@viewResponseLetter'
)->name('scholars.leaves.response_letter')->middleware('auth:web,scholars');

Route::get(
    '/scholars/@{scholar}/leaves/{leave}/application',
    'LeavesController@viewApplication'
)->name('scholars.leaves.application')->middleware('auth:web,scholars');

Route::post(
    '/scholars/@{scholar}/leaves',
    'LeavesController@store'
)->name('scholars.leaves.store')->middleware('auth:scholars');

Route::patch(
    '/scholars/@{scholar}/leaves/{leave}/recommend',
    'LeavesController@recommend'
)->name('scholars.leaves.recommend')->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/leaves/{leave}/respond',
    'LeavesController@respond'
)->name('scholars.leaves.respond')->middleware('auth:web');

//============ scholar advisory meetings =================
Route::get(
    '/scholars/@{scholar}/advisory-meetings',
    'AdvisoryMeetingsController@index'
)->name('scholars.advisory-meetings.index')->middleware('auth:web,scholars');

Route::post(
    '/scholars/@{scholar}/advisory-meetings',
    'AdvisoryMeetingsController@store'
)->name('scholars.advisory-meetings.store')->middleware('auth:web');

Route::get(
    '/scholars/@{scholar}/advisory-meetings/{meeting}',
    'AdvisoryMeetingsController@show'
)->name('scholars.advisory-meetings.show')->middleware('auth:web,scholars');

//============ scholar documents =====================
Route::get(
    'scholars/@{scholar}/documents',
    'ScholarDocumentsController@index'
)->name('scholars.documents.index')->middleware('auth:web,scholars');

Route::get(
    'scholars/@{scholar}/document/{document}',
    'ScholarDocumentsController@show'
)->name('scholars.documents.show')->middleware('auth:web,scholars');

Route::post(
    '/scholars/@{scholar}/document',
    'ScholarDocumentsController@store'
)->name('scholars.documents.store')->middleware('auth:web,scholars');

Route::delete(
    '/scholars/@{scholar}/document/{document}',
    'ScholarDocumentsController@destroy'
)->name('scholars.documents.destroy')->middleware('auth:web,scholars');

//=========== scholar pre-phd seminar =============
Route::get(
    '/scholars/@{scholar}/pre-phd-seminar',
    'PrePhdSeminarController@index'
)->name('scholars.pre-phd-seminar.index')->middleware('auth:web,scholars');

Route::get(
    '/scholars/@{scholar}/pre-phd-seminar/request',
    'PrePhdSeminarController@request'
)->name('scholars.pre-phd-seminar.request')->middleware('auth:scholars');

Route::post(
    '/scholars/@{scholar}/pre-phd-seminar/apply',
    'PrePhdSeminarController@apply'
)->name('scholars.pre-phd-seminar.apply')->middleware('auth:scholars');

Route::get(
    '/scholars/@{scholar}/pre-phd-seminar/{prePhdSeminar}/show',
    'PrePhdSeminarController@show'
)->name('scholars.pre-phd-seminar.show')->middleware('auth:web,scholars');

Route::patch(
    '/scholars/@{scholar}/appeals/{prePhdSeminar}/forward',
    'PrePhdSeminarController@forward'
)->name('scholars.pre-phd-seminar.forward')->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/appeals/{prePhdSeminar}/schedule',
    'PrePhdSeminarController@schedule'
)->name('scholars.pre-phd-seminar.schedule')->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/appeals/{prePhdSeminar}/finalize',
    'PrePhdSeminarController@finalize'
)->name('scholars.pre-phd-seminar.finalize')->middleware('auth:web');

//================Scholar Title Approval =======================

Route::get(
    '/scholars/@{scholar}/title-approval',
    'TitleApprovalController@index'
)->name('scholars.title-approval.index')
    ->middleware('auth:web,scholars');

Route::get(
    '/scholars/@{scholar}/title-approval/request',
    'TitleApprovalController@request'
)->name('scholars.title-approval.request')->middleware('auth:scholars');

Route::post(
    '/scholars/@{scholar}/title-approval/apply',
    'TitleApprovalController@apply'
)->name('scholars.title-approval.apply')->middleware('auth:scholars');

Route::get(
    '/scholars/@{scholar}/title-approval/{titleApproval}/show',
    'TitleApprovalController@show'
)->name('scholars.title-approval.show')->middleware('auth:web,scholars');

Route::patch(
    '/scholars/@{scholar}/title-approval/{titleApproval}/recommend',
    'TitleApprovalController@recommend'
)->name('scholars.title-approval.recommend')->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/title-approval/{titleApproval}/approve',
    'TitleApprovalController@approve'
)->name('scholars.title-approval.approve')->middleware('auth:web');

Route::middleware('user_profile')->group(function () {
	Route::get('/users/@{user}', 'UserProfileController@show')->name('profiles.show');
	Route::patch('/users/@{user}', 'UserProfileController@update')->name('profiles.update');
	Route::get('/users/@{user}/avatar', 'UserProfileController@avatar')->name('profiles.avatar');
});

// ----------- Teaching Records ------------------
Route::get('/teaching-records', 'TeachingRecordsController@index')->name('teaching-records.index');
Route::post('/teaching-records/submit', 'TeachingRecordsController@store')->name('teaching-records.submit');
Route::get('/teaching-records/export', 'TeachingRecordsController@export')->name('teaching-records.export');
Route::post('/teaching-records/start', 'TeachingRecordsController@start')->name('teaching-records.start');
Route::patch('/teaching-records/extend', 'TeachingRecordsController@extend')->name('teaching-records.extend');

//------------------- Teaching Details for currently logged in teacher only -------------
Route::get('/teaching-details', 'TeachingDetailsController@index')->name('teaching-details.index');
Route::post('/teaching-details', 'TeachingDetailsController@store')->name('teaching-details.store');
Route::delete('/teaching-details/{teachingDetail}', 'TeachingDetailsController@destroy')->name('teaching-details.destroy');

//===================Scholar Examiner==============
Route::get('scholars/@{scholar}/examiner', 'ExaminerController@index')
    ->middleware('auth:web,scholars')
    ->name('scholars.examiner.index');

Route::post('scholars/@{scholar}/examiner/apply', 'ExaminerController@apply')
    ->middleware('auth:web')
    ->name('scholars.examiner.apply');

Route::patch('scholars/@{scholar}/examiner/{examiner}/recommend', 'ExaminerController@recommend')
    ->middleware('auth:web')
    ->name('scholars.examiner.recommend');

Route::patch('scholars/@{scholar}/examiner/{examiner}/approve', 'ExaminerController@approve')
    ->middleware('auth:web')
    ->name('scholars.examiner.approve');

//================Scholar Courseworks=================
Route::get(
    '/scholars/@{scholar}/courseworks',
    'CourseworkController@index'
)->name('scholars.courseworks.index')
    ->middleware('auth:web,scholars');

Route::post(
    '/scholars/@{scholar}/coursework',
    'CourseworkController@store'
)->name('scholars.courseworks.store')
    ->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/coursework/{courseId}',
    'CourseworkController@complete'
)->name('scholars.courseworks.complete')
    ->middleware('auth:web');

Route::get(
    '/scholars/@{scholar}/courseworks/{course}/',
    'CourseworkController@show'
)->name('scholars.courseworks.marksheet')
    ->middleware('auth:web,scholars');

//========= User Publications=================
Route::get(
    '@{user}/publications',
    'UserPublicationController@index'
)->name('users.publications.index')
    ->middleware('auth:web');

Route::get(
    '@{user}/publications/create',
    'UserPublicationController@create'
)->name('users.publications.create')
    ->middleware('auth:web');

Route::get(
    '@{user}/publications/{publication}',
    'UserPublicationController@show'
)->name('users.publications.show')
    ->middleware('auth:web');

Route::post(
    '@{user}/publications/',
    'UserPublicationController@store'
)->name('users.publications.store')
    ->middleware('auth:web');

Route::get(
    '@{user}/publications/{publication}/edit',
    'UserPublicationController@edit'
)->name('users.publications.edit')
    ->middleware('auth:web');

Route::patch(
    '@{user}/publications/{publication}',
    'UserPublicationController@update'
)->name('users.publications.update')
    ->middleware('auth:web');

Route::delete(
    '@{user}/publications/{publication}',
    'UserPublicationController@destroy'
)->name('users.publications.destroy')
    ->middleware('auth:web');

//=========== Scholar Publications ==================

Route::get(
    'scholars/@{scholar}/publications',
    'ScholarPublicationController@index'
)->name('scholars.publications.index')
    ->middleware('auth:web,scholars');

Route::get(
    'scholars/@{scholar}/publications/create',
    'ScholarPublicationController@create'
)->name('scholars.publications.create')
    ->middleware('auth:scholars');

Route::get(
    'scholars/@{scholar}/publications/{publication}',
    'ScholarPublicationController@show'
)->name('scholars.publications.show')
    ->middleware('auth:web,scholars');

Route::post(
    'scholars/@{scholar}/publications/',
    'ScholarPublicationController@store'
)->name('scholars.publications.store')
    ->middleware('auth:scholars');

Route::get(
    'scholars/@{scholar}/publications/{publication}/edit',
    'ScholarPublicationController@edit'
)->name('scholars.publications.edit')
    ->middleware('auth:scholars');

Route::patch(
    'scholars/@{scholar}/publications/{publication}',
    'ScholarPublicationController@update'
)->name('scholars.publications.update')
    ->middleware('auth:scholars');

Route::delete(
    'scholars/@{scholar}/publications/{publication}',
    'ScholarPublicationController@destroy'
)->name('scholars.publications.destroy')
    ->middleware('auth:scholars');

//================= Publication Co-Authors===================
Route::get(
    'publications/{publication}/co-authors/{coAuthor}',
    'CoAuthorController@show'
)->name('publications.co-authors.show')
    ->middleware('auth:web,scholars');

Route::post(
    'publications/{publication}/co-authors',
    'CoAuthorController@store'
)->name('publications.co-authors.store')
    ->middleware('auth:web,scholars');

Route::delete(
    'publications/{publication}/co-authors/{coAuthor}',
    'CoAuthorController@destroy'
)->name('publications.co-authors.destroy')
    ->middleware('auth:web,scholars');

Route::prefix('/publications')
    ->middleware('auth:web,scholars')
    ->namespace('Publications')
    ->as('publications.')
    ->group(static function () {
    });

// ================Scholar Progress Reports==========

Route::get(
    '/scholars/@{scholar}/progress-reports',
    'ProgressReportController@index'
)->name('scholars.progress-reports.index')
    ->middleware('auth:web,scholars');

Route::post(
    '/scholars/@{scholar}/progress-report',
    'ProgressReportController@store'
)->name('scholars.progress-reports.store')
    ->middleware('auth:web');

Route::get(
    'scholars/@{scholar}/progress-report/{report}',
    'ProgressReportController@show'
)->name('scholars.progress-reports.show')
    ->middleware(['auth:web,scholars']);

Route::delete(
    'scholars/@{scholar}/progress-report/{report}',
    'ProgressReportController@destroy'
)->name('scholars.progress-reports.destroy')
    ->middleware('auth:web');

//===========Scholar-Profile==================
// 'scholar' middleware is unknown
Route::middleware(['scholar_profile'])->group(function () {
	Route::get('/scholars/@{scholar}', 'ScholarProfileController@show')->name('scholars.profile.show')->middleware('auth:web,scholars');
	Route::patch('/scholars/@{scholar}', 'ScholarProfileController@update')->name('scholars.profile.update')->middleware('auth:web,scholars');
	Route::get('/scholars/@{scholar}/avatar', 'ScholarProfileController@avatar')->name('scholars.profile.avatar')->middleware('auth:web,scholars');
});
Route::patch(
    '/scholars/@{scholar}/advisors',
    'ScholarProfileController@updateAdvisors'
)->name('scholars.advisors.update')
    ->middleware('auth:web');

Route::patch(
    '/scholars/@{scholar}/advisors/replace',
    'ScholarProfileController@replaceAdvisors'
)->name('scholars.advisors.replace')
    ->middleware('auth:web');

// =========Scholar Presentation===============
Route::get(
    '/scholars/@{scholar}/presentations',
    'PresentationController@index'
)->name('scholars.presentations.index')
    ->middleware('auth:scholars,web');

Route::get(
    '/scholars/@{scholar}/presentation',
    'PresentationController@create'
)->name('scholars.presentations.create')
    ->middleware('auth:scholars');

Route::post(
    '/scholars/@{scholar}/presentation',
    'PresentationController@store'
)->name('scholars.presentations.store')
    ->middleware('auth:scholars');

Route::get(
    '/scholars/@{scholar}/presentation/{presentation}/edit',
    'PresentationController@edit'
)->name('scholars.presentations.edit')
    ->middleware('auth:scholars');

Route::patch(
    '/scholars/@{scholar}/presentation/{presentation}',
    'PresentationController@update'
)->name('scholars.presentations.update')
    ->middleware('auth:scholars');

Route::delete(
    '/scholars/@{scholar}/presentation/{presentation}',
    'PresentationController@destroy'
)->name('scholars.presentations.destroy')
    ->middleware('auth:web,scholars');

Route::prefix('/scholars')
    ->middleware('auth:scholars')
    ->namespace('Scholars')
    ->as('scholars.')
    ->group(static function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::patch(
            'scholars/{scholar}/proposed-title/update',
            'ProposedTitleController@update'
        )->name('proposed_title.update');
    });
