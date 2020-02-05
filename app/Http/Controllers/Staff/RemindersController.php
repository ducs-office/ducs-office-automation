<?php

namespace App\Http\Controllers\Staff;

use App\LetterReminder;
use App\Http\Controllers\Controller;

class RemindersController extends Controller
{
    public function update(LetterReminder $reminder)
    {
        $this->authorize('update', $reminder);

        $data = request()->validate([
            'attachments' => 'required|array|min:1|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        $reminder->attachments()->createMany(
            array_map(function ($attachedFile) {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/outgoing/reminders')
                ];
            }, $data['attachments'])
        );

        return redirect()->back();
    }

    public function destroy(LetterReminder $reminder)
    {
        $this->authorize('delete', $reminder);

        $reminder->attachments->each->delete();

        $reminder->delete();

        return back();
    }
}
