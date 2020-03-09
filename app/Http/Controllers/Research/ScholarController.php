<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\PhdCourse;
use App\Scholar;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    public function index(Request $request)
    {
        $scholars = $request->user()
            ->load('supervisorProfile.scholars')
            ->supervisorProfile
            ->scholars;

        return view('research.scholars.index', ['scholars' => $scholars]);
    }

    public function show(Scholar $scholar)
    {
        return view('research.scholars.show', [
            'scholar' => $scholar->load(['profile', 'courseworks']),
            'categories' => config('options.scholars.categories'),
            'admission_criterias' => config('options.scholars.admission_criterias'),
            'courses' => PhdCourse::whereNotIn('id', $scholar->courseworks()->allRelatedIds())->get(),
        ]);
    }
}
