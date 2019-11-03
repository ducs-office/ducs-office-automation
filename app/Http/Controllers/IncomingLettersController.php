<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IncomingLetter;
use Auth;
use App\User;

class IncomingLettersController extends Controller
{
    public function create()
    {
        return view('incoming_letters.create');
    }

    public function store()
    {
        $data = request()->validate([
            'date' => 'required|date|before_or_equal:today',
            'received_id' => 'required|string',
            'sender' => 'required|string|max:50',
            'recipient_id' => 'required|exists:users,id',
            'handover_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:1,2,3',
            'subject' => 'required|string|max:80',
            'description' => 'nullable|string|max:400',
            'attachments' => 'required|array|max:1',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        $letter = IncomingLetter::create($data);

        $letter->attachments()->createMany(
            array_map(function ($attachedFile) 
            {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/incoming')
                ];
            }, request()->file('attachments'))
        );

        return redirect('/incoming-letters');
    }

    public function destroy(IncomingLetter $incoming_letter)
    {
        $incoming_letter->attachments->each->delete();
        $incoming_letter->remarks->each->delete();

        $incoming_letter->delete();

        return redirect('/incoming-letters');
    }
}
