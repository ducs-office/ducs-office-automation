<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LetterReminder;
use App\OutgoingLetter;

class RemindersController extends Controller
{
    //
    public function store()
    {
        $data = request()->validate([
            'letter_id'=>'required|exists:outgoing_letters,id'
        ]);

        LetterReminder::create($data);

        return back();
    }

    public function destroy(LetterReminder $reminder)
    {
        $reminder->delete();

        return back();
    }
}
