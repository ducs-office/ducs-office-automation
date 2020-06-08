<?php

namespace App\Http\Controllers;

use App\Exceptions\TeacherProfileNotCompleted;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Notifications\AcceptingTeachingRecordsStarted;
use App\Notifications\TeachingRecordsSaved;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class TeachingRecordsController extends Controller
{
    public function index(Request $request)
    {
        return view('teaching-records.index', [
            'records' => $this->getRecords($request),
            'courses' => Course::select(['id', 'code', 'name'])->get(),
            'startDate' => TeachingRecord::getStartDate(),
            'endDate' => TeachingRecord::getEndDate(),
        ]);
    }

    protected function export(Request $request)
    {
        $csv = $this->getRecords($request)
            ->toCsv($this->getCsvFields());

        $filename = 'teaching_records_' . time() . '.csv';
        $filepath = 'temp/exports/' . $filename;

        Storage::put($filepath, $csv);

        return Response::download(Storage::path($filepath), $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    public function store(Request $request)
    {
        $this->authorize('create', TeachingRecord::class);
        $teacher = $request->user();

        $this->ensureProfileCompleted($teacher);

        $teacher->teachingRecords()->createMany(
            $teacher->teachingDetails->map->toTeachingRecord()->toArray()
        );

        if ($request->notify) {
            $request->user()->notify(new TeachingRecordsSaved());
        }

        flash('Details submitted successfully!')->success();

        return redirect()->back();
    }

    public function start(Request $request)
    {
        $this->authorize('start', TeachingRecord::class);

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:' . $request->start_date,
        ]);

        TeachingRecord::startAccepting(
            $start = Carbon::parse($request->start_date),
            $end = Carbon::parse($request->end_date)
        );

        Notification::send(
            User::collegeTeachers()->get(),
            new AcceptingTeachingRecordsStarted($start, $end)
        );

        flash('Teachers can start submitting profiles.')->success();

        return redirect()->back();
    }

    public function extend(Request $request)
    {
        $this->authorize('extend', TeachingRecord::class);

        $request->validate([
            'extend_to' => 'required|date|after_or_equal:' . TeachingRecord::getEndDate(),
        ]);

        TeachingRecord::extendDeadline(
            Carbon::parse($request->extend_to)
        );

        flash('Deadline is extended!')->success();

        return redirect()->back();
    }

    private function getRecords(Request $request)
    {
        return TeachingRecord::with([
            'teacher', 'college', 'course',
            'programmeRevision.programme',
        ])
        ->filter($request->get('filters'))
        ->orderBy('valid_from', 'desc')
        ->get();
    }

    private function getCsvFields()
    {
        return [
            'Year' => 'valid_from.year',
            'Teacher' => 'teacher.name',
            'Status' => 'status',
            'Designation' => 'designation',
            'College' => 'college.name',
            'Course' => 'course.name',
            'Semester' => 'semester',
            'Programme' => 'programmeRevision.programme.name',
        ];
    }

    private function ensureProfileCompleted(User $teacher)
    {
        if (! $teacher->isProfileComplete()) {
            throw new TeacherProfileNotCompleted(
                'Your profile is not completed. You cannot perform this action.'
            );
        }
    }
}
