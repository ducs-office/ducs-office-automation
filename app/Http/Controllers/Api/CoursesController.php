<?php
    
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('q') || $request->q == '') {
            return [];
        }

        $query = Course::where('code', 'like', $request->q)
                ->orWhere('name', 'like', "%{$request->q}%")
                ->limit($request->limit)
                ->get();
            
        return $query;
    }

    public function show(Course $course)
    {
        return $course;
    }
}
