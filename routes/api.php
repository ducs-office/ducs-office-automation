<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(static function () {
    Route::get('/users', 'UsersController@index');
    Route::get('/users/{user}', 'UsersController@show');
});

Route::middleware('auth:web,teachers')->group(static function () {
    Route::get('/courses', 'CoursesController@index');
    Route::get('/courses/{course}', 'CoursesController@show');
    Route::get(
        '/programme-revisions/{programmeRevision}/courses',
        'ProgrammeRevisionCourseController@index'
    );
});
