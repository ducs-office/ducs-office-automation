<?php
    
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
        
        if ($request->has('q')) {
            $query->where('code', 'like', "%{$request->q}%")
                ->orWhere('name', 'like', "%{$request->q}%");
        }

        if ($request->has('without_programme')) {
            $query->whereDoesntHave('programmes');
        }
        
        return $query->limit($request->limit)->get();
    }

    public function show(Course $course)
    {
        return $course;
    }
}
