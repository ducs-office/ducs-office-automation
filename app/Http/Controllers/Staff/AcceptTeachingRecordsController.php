<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\PastTeachersProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AcceptingTeachingRecordsStarted;
use App\Teacher;

class AcceptTeachingRecordsController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:'.$request->start_date
        ]);

        PastTeachersProfile::startAccepting(
            Carbon::parse($request->start_date),
            Carbon::parse($request->end_date)
        );
        
        $teachers = Teacher::all();

        Notification::send($teachers, new AcceptingTeachingRecordsStarted(
            PastTeachersProfile::getStartDate()->format('d-m-Y'),
            PastTeachersProfile::getEndDate()->format('d-m-Y')
        ));
       
        
        flash('Teachers can start submitting profiles.')->success();

        return redirect()->back();
    }
    
    public function extend(Request $request)
    {
        $request->validate([
            'extend_to' => 'required|date|after_or_equal:'.PastTeachersProfile::getEndDate(),
        ]);
        PastTeachersProfile::extendDeadline(
            Carbon::parse($request->extend_to)
        );

        flash('Deadline is extended!')->success();

        return redirect()->back();
    }
}
