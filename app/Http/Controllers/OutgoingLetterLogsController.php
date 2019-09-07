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
        OutgoingLetterLog::create($request->only([
            'date', 
            'type', 
            'recipient', 
            'sender_id', 
            'description', 
            'amount'
        ]));
        
        return redirect('/outgoing-letter-logs');
    }
    
}
