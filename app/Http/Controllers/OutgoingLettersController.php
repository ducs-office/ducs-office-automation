<?php

namespace App\Http\Controllers;

use Auth;
use App\OutgoingLetter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Cache;
    

use DB;

class OutgoingLettersController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->query('filters');
        
        $query = OutgoingLetter::applyFilter($filters);

        if ($request->has('search') && request('search')!= '') {
            $query->where('subject', 'like', '%'.request('search').'%')
                ->orWhere('description', 'like', '%'.request('search').'%');
        }

        $outgoing_letters = $query->orderBy('date', 'DESC')->get();

        $outgoing_letters->load(['remarks'=>function($query){
            $query->orderBy('updated_at', 'DESC');
            } , 'reminders'=>function($query){
            $query->orderBy('created_at','DESC');
        }]);

        $recipients = OutgoingLetter::selectRaw('DISTINCT(recipient)')->get()->pluck('recipient', 'recipient');
        $types = OutgoingLetter::selectRaw('DISTINCT(type)')->get()->pluck('type', 'type');
        $senders = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(sender_id)'))->get()->pluck('name', 'id');
        $creators = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(creator_id)'))->get()->pluck('name', 'id');

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
            'type' => 'required|in:Bill,Notesheet,General',
            'recipient' => 'required',
            'sender_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:80',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'pdf' => 'required_without:scan|max:200|mimes:pdf',
            'scan' => 'required_without:pdf|max:200|mimes:jpeg,jpg,png,pdf'
        ]);
        
        
        $type = $validData['type'];
        $validData['serial_no'] = 'CS/';
        
        if($type == 'Bill') {
            $validData['serial_no'] .= 'TR/';
        }
        else if($type == 'Notesheet') {
            $validData['serial_no'] .= 'NTS/';
        }
        
        $validData['serial_no'] .= now()->year.'/';
        
        if(Cache::has($type)) {
            Cache::increment($type);
        }
        else {
            Cache::put($type, 1, 525600);    //1 year in miuntes
        }
        
        $serial_no_val = Cache::get($type);
        $validData['serial_no'] .= str_pad($serial_no_val,4,'0',STR_PAD_LEFT);

        $pdf = request()->file('pdf');
        $scan = request()->file('scan');

        if($pdf) {
            $validData['pdf'] = $pdf->store('letters/outgoing');
        }
        if($scan) {
            $validData['scan'] = $scan->store('letters/outgoing');
        }

        OutgoingLetter::create($validData + ['creator_id'=>Auth::id()]);

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
            'type' => 'sometimes|required|in:Bill,Notesheet,General',
            'recipient' =>  'sometimes|required|',
            'subject' => 'sometimes|required|string|max:80',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'sender_id' => 'sometimes|required|exists:users,id',
            'pdf' => 'sometimes|required_without:scan|max:200|mimes:pdf',
            'scan' => 'sometimes|required_without:pdf|max:200|mimes:jpeg,jpg,png,pdf'
        ]);
        
        $pdf = request()->file('pdf');
        $scan = request()->file('scan');

        if($pdf) {
            $validData['pdf'] = $pdf->store('letters/outgoing');
        }
        if($scan) {
            $validData['scan'] = $scan->store('letters/outgoing');
        }
        
        $outgoing_letter->update($validData);

        return redirect('/outgoing-letters');
    }

    public function destroy(OutgoingLetter $outgoing_letter)
    {
        $pdf = $outgoing_letter['pdf'];
        $scan = $outgoing_letter['scan'];

        if($pdf) 
        {
            Storage::delete($pdf);
        }
        if($scan)
        {
            Storage::delete($scan);
        }
        
        $outgoing_letter->delete();

        return redirect('/outgoing-letters');
    }
}
