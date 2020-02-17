<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('staff.dashboard');
})->name('dashboard');

Route::post('/account/change_password', 'AccountController@change_password')->name('account.change_password');

Route::get('/outgoing-letters/create', 'OutgoingLettersController@create')->name('outgoing_letters.create');
Route::post('/outgoing-letters', 'OutgoingLettersController@store')->name('outgoing_letters.store');
Route::get('/outgoing-letters/{outgoing_letter}/edit', 'OutgoingLettersController@edit')->name('outgoing_letters.edit');
Route::get('/outgoing-letters', 'OutgoingLettersController@index')->name('outgoing_letters.index');
Route::patch('/outgoing-letters/{outgoing_letter}', 'OutgoingLettersController@update')->name('outgoing_letters.update');
Route::delete('/outgoing-letters/{outgoing_letter}', 'OutgoingLettersController@destroy')->name('outgoing_letters.destroy');

Route::get('/incoming-letters/create', 'IncomingLettersController@create')->name('incoming_letters.create');
Route::post('/incoming-letters', 'IncomingLettersController@store')->name('incoming_letters.store');
Route::delete('/incoming-letters/{incoming_letter}', 'IncomingLettersController@destroy')->name('incoming_letters.destroy');
Route::get('/incoming-letters', 'IncomingLettersController@index')->name('incoming_letters.index');
Route::get('/incoming-letters/{incoming_letter}/edit', 'IncomingLettersController@edit')->name('incoming_letters.edit');
Route::patch('/incoming-letters/{incoming_letter}', 'IncomingLettersController@update')->name('incoming_letters.update');

Route::post('/outgoing-letters/{outgoing_letter}/remarks', 'OutgoingLettersController@storeRemark')->name('outgoing_letters.remarks.store');
Route::post('/incoming-letters/{incoming_letter}/remarks', 'IncomingLettersController@storeRemark')->name('incoming_letters.remarks.store');

Route::post('/outgoing_letters/{letter}/reminders', 'OutgoingLetterRemindersController@store')->name('outgoing_letters.reminders.store');

Route::patch('/remarks/{remark}', 'RemarksController@update')->name('remarks.update');
Route::delete('/remarks/{remark}', 'RemarksController@destroy')->name('remarks.destroy');

Route::delete('/reminders/{reminder}', 'RemindersController@destroy')->name('reminders.destroy');
Route::patch('/reminders/{reminder}', 'RemindersController@update')->name('reminders.update');

Route::get('/programmes', 'ProgrammesController@index')->name('programmes.index');
Route::get('/programmes/create', 'ProgrammesController@create')->name('programmes.create');
Route::post('/programmes', 'ProgrammesController@store')->name('programmes.store');
Route::get('/programmes/{programme}/edit', 'ProgrammesController@edit')->name('programmes.edit');
Route::patch('/programmes/{programme}', 'ProgrammesController@update')->name('programmes.update');
Route::delete('/programmes/{programme}', 'ProgrammesController@destroy')->name('programmes.destroy');

Route::get('/programme/{programme}/revisions', 'ProgrammeRevisionController@index')->name('programmes.revisions.show');
Route::get('/programmes/{programme}/revisions/create', 'ProgrammeRevisionController@create')->name('programmes.revisions.create');
Route::post('/programmes/{programme}/revisions', 'ProgrammeRevisionController@store')->name('programmes.revisions.store');
Route::get('/programmes/{programme}/revisions/{programme_revision}/edit', 'ProgrammeRevisionController@edit')->name('programmes.revisions.edit');
Route::patch('/programmes/{programme}/revisions/{programme_revision}', 'ProgrammeRevisionController@update')->name('programmes.revisions.update');
Route::delete('/programme/{programme}/revisions/{programmeRevision}', 'ProgrammeRevisionController@destroy')->name('programmes.revisions.destroy');

Route::get('/courses', 'CourseController@index')->name('courses.index');
Route::post('/courses', 'CourseController@store')->name('courses.store');
Route::patch('/courses/{course}', 'CourseController@update')->name('courses.update');
Route::delete('/courses/{course}', 'CourseController@destroy')->name('courses.destroy');
Route::post('/courses/{course}/revisions', 'CourseRevisionController@store')->name('courses.revisions.store');
Route::delete('/courses/{course}/revisions/{course_revision}', 'CourseRevisionController@destroy')->name('courses.revisions.destroy');

Route::get('/colleges', 'CollegeController@index')->name('colleges.index');
Route::post('/colleges', 'CollegeController@store')->name('colleges.store');
Route::get('/colleges/create', 'CollegeController@create')->name('colleges.create');
Route::get('/colleges/{college}/edit', 'CollegeController@edit')->name('colleges.edit');
Route::patch('/colleges/{college}', 'CollegeController@update')->name('colleges.update');
Route::delete('/colleges/{college}', 'CollegeController@destroy')->name('colleges.destroy');

Route::get('/users', 'UserController@index')->name('users.index');
Route::post('/users', 'UserController@store')->name('users.store');
Route::patch('/users/{user}', 'UserController@update')->name('users.update');
Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');

Route::get('/roles', 'RoleController@index')->name('roles.index');
Route::post('/roles', 'RoleController@store')->name('roles.store');
Route::patch('/roles/{role}', 'RoleController@update')->name('roles.update');
Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy');

Route::get('/attachments/{attachment}', 'AttachmentController@show')->name('attachments.show');
Route::delete('/attachments/{attachment}', 'AttachmentController@destroy')->name('attachments.destroy');

Route::get('/teachers', 'TeacherController@index')->name('teachers.index');
Route::post('/teachers', 'TeacherController@store')->name('teachers.store');
Route::patch('/teachers/{teacher}', 'TeacherController@update')->name('teachers.update');
Route::delete('/teachers/{teacher}', 'TeacherController@destroy')->name('teachers.destroy');
