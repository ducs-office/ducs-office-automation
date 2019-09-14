<?php

namespace App\Http\Controllers;

use Auth;
use App\OutgoingLetter;
use Illuminate\Http\Request;
use DB;

class OutgoingLettersController extends Controller
{
    public function index(Request $request)
    {
        $outgoing_letters = OutgoingLetter::whereBetween('date',[
            $request->after ?? OutgoingLetter::min('date'),
            $request->before ?? OutgoingLetter::max('date')
        ])->get();

        return view('outgoing_letters.index', compact('outgoing_letters'));
    }

    public function create()
    {
        return view('outgoing_letters.create');
    }
    
    protected function store(Request $request)
    {
        $validData = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'type' => 'required',
            'recipient' => 'required',
            'sender_id' => 'required|exists:users,id',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
        ]);
        
        OutgoingLetter::create($validData);
        
        return redirect('/outgoing-letters');
    }

    public function edit(OutgoingLetter $outgoing_letter)
    {
        return view('outgoing_letters.edit', compact('outgoing_letter'));
    }

    public function update(OutgoingLetter $outgoing_letter, Request $request)
    {
        $validData = $request->validate([
            'date' => 'sometimes|required|date|before_or_equal:today',
            'type' => 'sometimes|required',
            'recipient' =>  'sometimes|required|',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'sender_id' => 'sometimes|required|exists:users,id'
        ]);

        $outgoing_letter->update($validData);
        
        return redirect('/outgoing-letters');
    }

    public function destroy(OutgoingLetter $outgoing_letter) 
    {
        $outgoing_letter->delete();
        
        return redirect('/outgoing-letters');
    }
}
