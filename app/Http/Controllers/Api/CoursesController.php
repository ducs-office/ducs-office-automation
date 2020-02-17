<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->has('q')) {
            $query->where('code', 'like', "{$request->q}%");
        }

        if ($request->has('without_programme')) {
            $query->whereDoesntHave('programmes');
        }

        return $query->get();
    }

    public function show(Course $course)
    {
        return $course;
    }
}
