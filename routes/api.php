<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::get('/users', 'UsersController@index');
    Route::get('/users/{user}', 'UsersController@show');
});
