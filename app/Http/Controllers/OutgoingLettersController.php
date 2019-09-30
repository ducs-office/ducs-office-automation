<?php

namespace App\Http\Controllers;

use Auth;
use App\OutgoingLetter;
use App\User;
use Illuminate\Http\Request;
use DB;

class OutgoingLettersController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query('filters');
        
        if($request->has('search') && request('search')!= '') {
            $outgoing_letters = OutgoingLetter::applyFilter($filters)->where('subject','like','%'.request('search').'%')->orWhere('description','like','%'.request('search').'%')->orderBy('date','DESC')->get();
         }else{
            $outgoing_letters = OutgoingLetter::applyFilter($filters)->orderBy('date','DESC')->get();
         }

        $recipients = OutgoingLetter::selectRaw('DISTINCT(recipient)')->get()->pluck('recipient')->toArray();
        $types = OutgoingLetter::selectRaw('DISTINCT(type)')->get()->pluck('type')->toArray();
        $senders = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(sender_id)'))->get()->toArray();
        $creators = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(creator_id)'))->get()->toArray();
        
        return view('outgoing_letters.index', compact(
            'outgoing_letters',
            'types', 
            'recipients',
            'creators',
            'senders'
        ));
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
            'subject' => 'required|string|max:80',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
        ]);
        
        OutgoingLetter::create($validData + ['creator_id'=> Auth::id()]);
        
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
            'subject' => 'sometimes|required|string|max:80',
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
