<?php

namespace App\Http\Controllers\Research;

use App\AdvisoryMeeting;
use App\Attachment;
use App\Http\Controllers\Controller;
use App\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdvisoryMeetingsController extends Controller
{
    public function store(Request $request, Scholar $scholar)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'minutes_of_meeting' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:200'],
        ]);

        $filename = strtotime($request->date)
            . '_' . str_slug($scholar->name, '_') . 'minutes_of_meeting.'
            . '_' . $request->file('minutes_of_meeting')->getClientOriginalExtension();

        $filePath = $request->file('minutes_of_meeting')->storeAs('advisory_meetings', $filename);

        DB::beginTransaction();

        $scholar->advisoryMeetings()->create([
            'date' => $request->date,
            'minutes_of_meeting_path' => $filePath,
        ]);

        DB::commit();

        flash('Advisory committee meeting saved!')->success();

        return redirect()->back();
    }

    public function minutesOfMeeting(AdvisoryMeeting $meeting)
    {
        abort_unless(
            Storage::exists($meeting->minutes_of_meeting_path),
            404,
            'File Not Found!'
        );

        return response()->file(Storage::path($meeting->minutes_of_meeting_path));
    }
}
