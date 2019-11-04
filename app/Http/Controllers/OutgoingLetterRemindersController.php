<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
// use lluminate\Contracts\Routing\ResponseFactory;
use App\LetterReminder;
use App\OutgoingLetter;

class OutgoingLetterRemindersController extends Controller
{
    public function store(Request $request, OutgoingLetter $letter)
    {
        $this->authorize('create', [LetterReminder::class, $letter]);

        $validData = request()->validate([
            'attachments' => 'required|array|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        $letter_reminder = $letter->reminders()->create($validData);

        $letter_reminder->attachments()->createMany(
            array_map(function ($attachedFile) {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/outgoing/reminders')
                ];
            }, $request->file('attachments'))
        );

        return redirect()->back();
    }
}
