<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    public function index()
    {
        return view('teachers.dashboard');
    }
}
