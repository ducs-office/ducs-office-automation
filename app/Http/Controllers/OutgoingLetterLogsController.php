<?php

namespace App\Http\Controllers;

use App\OutgoingLetterLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutgoingLetterLogsController extends Controller
{
    public function create()
    {
        return view('outgoing_letter_logs.create');
    }
    
    protected function store(Request $request) 
    {
        $today = date('Y-m-d H:i:s');
        $validData = $request->validate([
            'date' => 'required|date_format:Y-m-d H:i:s|before_or_equal:'.$today,
            'type' => 'required',
            'recipient' =>  'required|',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'sender_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if(2===2) {
                        $fail($attribute.' is invalid.');
                    }
                },
            ],
        ]);
        
        OutgoingLetterLog::create($validData);
        
        return redirect('/outgoing-letter-logs');
    }
    
}
