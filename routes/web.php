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
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/login', 'Auth\LoginController@showLoginForm')->middleware('guest')->name('login');
Route::post('/login', 'Auth\LoginController@login')->middleware('guest')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->middleware('auth')->name('logout');

Route::post('/account/change_password', 'AccountController@change_password')->middleware('auth')->name('account.change_password');

Route::get('/outgoing-letters/create', 'OutgoingLettersController@create')->middleware('auth')->name('outgoing_letters.create');
Route::post('/outgoing-letters', 'OutgoingLettersController@store')->middleware('auth')->name('outgoing_letters.store');
Route::get('/outgoing-letters/{outgoing_letter}/edit', 'OutgoingLettersController@edit')->middleware('auth')->name('outgoing_letters.edit');
Route::get('/outgoing-letters', 'OutgoingLettersController@index')->middleware('auth')->name('outgoing_letters.index');
Route::patch('/outgoing-letters/{outgoing_letter}', 'OutgoingLettersController@update')->middleware('auth')->name('outgoing_letters.update');
Route::delete('/outgoing-letters/{outgoing_letter}', 'OutgoingLettersController@destroy')->middleware('auth')->name('outgoing_letters.destroy');

Route::get('/incoming-letters/create', 'IncomingLettersController@create')->middleware('auth')->name('incoming_letters.create');
Route::post('/incoming-letters', 'IncomingLettersController@store')->middleware('auth')->name('incoming_letters.store');
Route::delete('/incoming-letters/{incoming_letter}', 'IncomingLettersController@destroy')->middleware('auth')->name('incoming_letters.destroy');
Route::get('/incoming-letters', 'IncomingLettersController@index')->middleware('auth')->name('incoming_letters.index');
Route::get('/incoming-letters/{incoming_letter}/edit', 'IncomingLettersController@edit')->middleware('auth')->name('incoming_letters.edit');
Route::patch('/incoming-letters/{incoming_letter}', 'IncomingLettersController@update')->middleware('auth')->name('incoming_letters.update');

Route::post('/outgoing-letters/{outgoing_letter}/remarks', 'OutgoingLettersController@storeRemark')->middleware('auth')->name('outgoing_letters.remarks.store');
Route::post('/incoming-letters/{incoming_letter}/remarks', 'IncomingLettersController@storeRemark')->middleware('auth')->name('incoming_letters.remarks.store');

Route::post('/outgoing_letters/{letter}/reminders', 'OutgoingLetterRemindersController@store')->middleware('auth')->name('outgoing_letters.reminders.store');

Route::patch('/remarks/{remark}', 'RemarksController@update')->middleware('auth')->name('remarks.update');
Route::delete('/remarks/{remark}', 'RemarksController@destroy')->middleware('auth')->name('remarks.destroy');

Route::delete('/reminders/{reminder}', 'RemindersController@destroy')->middleware('auth')->name('reminders.destroy');
Route::patch('/reminders/{reminder}', 'RemindersController@update')->middleware('auth')->name('reminders.update');


Route::get('/programmes', 'ProgrammesController@index')->middleware('auth')->name('programmes.index');
Route::get('/programmes/create', 'ProgrammesController@create')->middleware('auth')->name('programmes.create');
Route::post('/programmes', 'ProgrammesController@store')->middleware('auth')->name('programmes.store');
Route::get('/programmes/{programme}/edit', 'ProgrammesController@edit')->middleware('auth')->name('programmes.edit');
Route::patch('/programmes/{programme}', 'ProgrammesController@update')->middleware('auth')->name('programmes.update');
Route::delete('/programmes/{programme}', 'ProgrammesController@destroy')->middleware('auth')->name('programmes.destroy');
Route::get('/programmes/{programme}/upgrade', 'ProgrammesController@upgrade')->middleware('auth')->name('programmes.upgrade');
Route::patch('/programmes/{programme}/revise', 'ProgrammesController@revise')->middleware('auth')->name('programmes.revise');

Route::get('/programme/{programme}/revisions', 'ProgrammeRevisionController@index')->middleware('auth')->name('programme_revisions.show');
Route::delete('/programme/{programme}/revisions/{programmeRevision}', 'ProgrammeRevisionController@destroy')->middleware('auth')->name('programme_revisions.destroy');

Route::get('/courses', 'CourseController@index')->middleware('auth')->name('courses.index');
Route::post('/courses', 'CourseController@store')->middleware('auth')->name('courses.store');
Route::patch('/courses/{course}', 'CourseController@update')->middleware('auth')->name('courses.update');
Route::delete('/courses/{course}', 'CourseController@destroy')->middleware('auth')->name('courses.destroy');

Route::get('/colleges', 'CollegeController@index')->middleware('auth')->name('colleges.index');
Route::post('/colleges', 'CollegeController@store')->middleware('auth')->name('colleges.store');
Route::get('/colleges/create', 'CollegeController@create')->middleware('auth')->name('colleges.create');
Route::get('/colleges/{college}/edit', 'CollegeController@edit')->middleware('auth')->name('colleges.edit');
Route::patch('/colleges/{college}', 'CollegeController@update')->middleware('auth')->name('colleges.update');
Route::delete('/colleges/{college}', 'CollegeController@destroy')->middleware('auth')->name('colleges.destroy');

Route::get('/users', 'UserController@index')->middleware('auth')->name('users.index');
Route::post('/users', 'UserController@store')->middleware('auth')->name('users.store');
Route::patch('/users/{user}', 'UserController@update')->middleware('auth')->name('users.update');
Route::delete('/users/{user}', 'UserController@destroy')->middleware('auth')->name('users.destroy');

Route::get('/roles', 'RoleController@index')->middleware('auth')->name('roles.index');
Route::post('/roles', 'RoleController@store')->middleware('auth')->name('roles.store');
Route::patch('/roles/{role}', 'RoleController@update')->middleware('auth')->name('roles.update');
Route::delete('/roles/{role}', 'RoleController@destroy')->middleware('auth')->name('roles.destroy');

Route::get('/attachments/{attachment}', 'AttachmentController@show')->middleware('auth')->name('attachments.show');
Route::delete('/attachments/{attachment}', 'AttachmentController@destroy')->middleware('auth')->name('attachments.destroy');
