<?php

namespace App\Http\Controllers;

use App\OutgoingLetterLog;
use Illuminate\Http\Request;

class OutgoingLetterLogsController extends Controller
{
    public function create()
    {
        return view('outgoing_letter_logs.create');
    }
    
    protected function store(Request $request) 
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d|date',
            'type' => 'required',
            'recipient' => 'required',
            'sender_id' => 'required|exists:users,id',
            'description' => 'string|max:400|nullable',
            'amount' => 'numeric|nullable',
            ]);
        OutgoingLetterLog::create($request->only([
            'date' , 
            'type', 
            'recipient', 
            'sender_id', 
            'description', 
            'amount'
        ]));
        
        return redirect('/outgoing-letter-logs');
    }

    public function edit(OutgoingLetterLog $letter) 
    {
        return view('outgoing_letter_logs.edit' , ['outgoing_letter'=> $letter]);
    }

    public function view() 
    {
        $letters = \App\OutgoingLetterLog::all();
        return view('outgoing_letter_logs.index' ,['outgoing_letter_logs' => $letters]);
    }
    
}
