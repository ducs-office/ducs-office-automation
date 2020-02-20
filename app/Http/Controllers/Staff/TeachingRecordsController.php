<?php

namespace App\Http\Controllers\Staff;

use App\Course;
use App\Http\Controllers\Controller;
use App\TeachingRecord;
use Illuminate\Http\Request;

class TeachingRecordsController extends Controller
{
    public function index(Request $request)
    {
        $records = TeachingRecord::with([
            'teacher', 'college', 'course',
            'programmeRevision.programme',
        ])->filter($request->get('filters'))->get();

        return view('staff.teaching_records.index', [
            'records' => $records,
            'courses' => Course::select(['id', 'code', 'name'])->get(),
            'startDate' => TeachingRecord::getStartDate(),
            'endDate' => TeachingRecord::getEndDate(),
        ]);
    }
}
