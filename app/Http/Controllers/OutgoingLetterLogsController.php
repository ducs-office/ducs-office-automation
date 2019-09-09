<?php

namespace App\Http\Controllers;

use App\OutgoingLetterLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingLetterLogsController extends Controller
{
    public function index(Request $request) 
    {
        if($request->has('before') && $request->has('after') ) 
            $outgoing_letter_logs = DB::table('outgoing_letter_logs')->whereBetween('date', [$request->after, $request->before])->get();
        
        else if($request->has('before')) 
            $outgoing_letter_logs = DB::table('outgoing_letter_logs')->where('date', '<', $request->before)->get();
        
        else if($request->has('after')) 
            $outgoing_letter_logs = DB::table('outgoing_letter_logs')->where('date', '>', $request->after)->get();
        else
            $outgoing_letter_logs = OutgoingLetterLog::all();
        return view('outgoing_letter_logs.index',compact('outgoing_letter_logs'));
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
    
    public function filterOnDate(Request $request) 
    {
        // $outgoing_letter_logs => [];
        
       
        return view('outgoing_letter_logs.index',compact('outgoing_letter_logs'));
    }
}
