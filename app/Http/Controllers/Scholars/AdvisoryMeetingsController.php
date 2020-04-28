<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdvisoryMeetingsController extends Controller
{
    public function minutesOfMeeting(AdvisoryMeeting $meeting)
    {
        abort_unless($meeting->scholar->id === auth()->id(), 403, 'You cannot view this file!');

        return Response::download(
            Storage::path($meeting->minutes_of_meeting_path),
            Str::after($meeting->minutes_of_meeting_path, '/'),
            [],
            'inline'
        );
    }
}
