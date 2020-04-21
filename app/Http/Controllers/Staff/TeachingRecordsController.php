<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TeachingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class TeachingRecordsController extends Controller
{
    public function index(Request $request)
    {
        $records = TeachingRecord::with([
            'teacher', 'college', 'course',
            'programmeRevision.programme',
        ])->filter($request->get('filters'))
        ->orderBy('valid_from', 'desc')
        ->get();

        if (Route::is('staff.teaching_records.export')) {
            return $this->exportAndSendCSV($records);
        }

        return view('staff.teaching_records.index', [
            'records' => $records,
            'courses' => Course::select(['id', 'code', 'name'])->get(),
            'startDate' => TeachingRecord::getStartDate(),
            'endDate' => TeachingRecord::getEndDate(),
        ]);
    }

    protected function exportAndSendCSV(Collection $records)
    {
        $csv = $records->toCsv($this->getCsvFields());

        $filename = 'teaching_records_' . time() . '.csv';
        $filepath = 'temp/exports/' . $filename;

        Storage::put($filepath, $csv);

        return Response::download(Storage::path($filepath), $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    protected function getCsvFields()
    {
        return [
            'Year' => 'valid_from.year',
            'Teacher' => 'teacher.name',
            'Designation' => 'fullDesignation',
            'College' => 'college.name',
            'Course' => 'course.name',
            'Semester' => 'semester',
            'Programme' => 'programmeRevision.programme.name',
        ];
    }
}
