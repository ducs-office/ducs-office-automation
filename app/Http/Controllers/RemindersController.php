<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\LetterReminder;
use App\OutgoingLetter;

class RemindersController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'letter_id'=>'required|exists:outgoing_letters,id',
            'pdf' => 'required_without:scan|max:200|mimes:pdf',
            'scan' => 'required_without:pdf|max:200|mimes:jpeg,jpg,png'
        ]);
        
        $pdf = request()->file('pdf');
        $scan = request()->file('scan');
        if($pdf)
            $data['pdf']= $pdf->store('letters/outgoing/reminders');
        if($scan)
            $data['scan'] = $scan->store('letters/outgoing/reminders');
        
        LetterReminder::create($data);
        return back();
    }

    public function show(LetterReminder $reminder) 
    {
        return Storage::download($reminder->pdf, $reminder->id);
    }

    public function update(LetterReminder $reminder)
    {
        $data = request()->validate([
            'pdf' => 'required_without:scan|max:200|mimes:pdf',
            'scan' => 'required_without:pdf|max:200|mimes:jpeg,jpg,png'
        ]);
        
        $pdf = request()->file('pdf');
        $scan = request()->file('scan');
        if($pdf)
            $data['pdf']= $pdf->store('letters/outgoing/reminders');
        if($scan)
            $data['scan'] = $scan->store('letters/outgoing/reminders');
        
        $reminder->update($data);
        return back();
    }

    public function destroy(LetterReminder $reminder)
    {
        $reminder->delete();

        return back();
    }




}
