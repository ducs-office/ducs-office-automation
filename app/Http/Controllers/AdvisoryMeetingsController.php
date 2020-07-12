<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryMeeting;
use App\Models\Attachment;
use App\Models\Scholar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdvisoryMeetingsController extends Controller
{
    public function index(Scholar $scholar)
    {
        $this->authorize('viewAny', [AdvisoryMeeting::class]);

        return view('advisory-meetings', [
            'scholar' => $scholar->load('advisoryMeetings'),
        ]);
    }

    public function store(Request $request, Scholar $scholar)
    {
        $this->authorize('create', [AdvisoryMeeting::class, $scholar]);

        $data = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'minutes_of_meeting' => ['required', 'file', 'mimetypes:application/pdf,image/*', 'max:200'],
        ]);

        $filename = strtotime($request->date)
            . '_' . Str::slug($scholar->name, '_') . 'minutes_of_meeting.'
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

    public function show(Scholar $scholar, AdvisoryMeeting $meeting)
    {
        $this->authorize('view', [$meeting, $scholar]);

        return response()->file(Storage::path($meeting->minutes_of_meeting_path));
    }
}
