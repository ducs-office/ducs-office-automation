<?php

namespace App\Http\Controllers;

use Auth;
use App\OutgoingLetterLog;
use App\User;
use Illuminate\Http\Request;

class OutgoingLetterLogsController extends Controller
{
    public function index()
    {
        $outgoing_letter_logs = OutgoingLetterLog::all();
        return view('outgoing_letter_logs.index', compact('outgoing_letter_logs'));
    }

    public function create()
    {
        return view('outgoing_letter_logs.create');
    }
    
    protected function store(Request $request)
    {
        $validData = $request->validate([
            'date' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'type' => 'required',
            'recipient' => 'required',
            'sender_id' => 'required|exists:users,id',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
        ]);
        
        OutgoingLetterLog::create($validData);
        
        return redirect('/outgoing-letter-logs');
    }

    public function edit(OutgoingLetterLog $outgoing_letter)
    {
        return view('outgoing_letter_logs.edit', compact('outgoing_letter'));
    }

    public function update(OutgoingLetterLog $outgoing_letter, Request $request)
    {
        $validData = $request->validate([
            'date' => 'nullable|date|date_format:Y-m-d|before_or_equal:today',
            'type' => 'nullable',
            'recipient' =>  'nullable|',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'sender_id' => 'nullable|exists:users,id'
        ]);

        $outgoing_letter->update($validData);
        
        return redirect('/outgoing-letter-logs');
    }
}
