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
        $outgoing_letter = OutgoingLetterLog::all();
        return view('outgoing_letter_logs.index',compact('outgoing_letter'));
    }
    public function create()
    {
        return view('outgoing_letter_logs.create');
    }
    
    public function edit(OutgoingLetterLog $outgoing_letter) 
    {   
        return view('outgoing_letter_logs.edit', compact('outgoing_letter'));
    }

    protected function store(Request $request) 
    {
        
        $validData = $request->validate([
            'date' => 'required|date_format:Y-m-d H:i:s|before_or_equal: today',
            'type' => 'required',
            'recipient' =>  'required|',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'sender_id' => 'required|exists:users,id'
        ]);
        
        OutgoingLetterLog::create($validData);
        
        return redirect('/outgoing-letter-logs');
    }

    public function update($id, Request $request) 
    {
        $validData = $request->validate([
            'date' => 'nullable|date_format:Y-m-d H:i:s|before:today',
            'type' => 'nullable',
            'recipient' =>  'nullable|',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'sender_id' => 'nullable|exists:users,id'
        ]);
        OutgoingLetterLog::where('id', $id)->update($validData);
        return redirect('/outgoing-letter-logs');
    }
    
}
