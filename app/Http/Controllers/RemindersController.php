<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
// use lluminate\Contracts\Routing\ResponseFactory;
use App\LetterReminder;

class RemindersController extends Controller
{
    public function store()
    {
        $validData = request()->validate([
            'letter_id'=>'required|exists:outgoing_letters,id',
            'pdf' => 'required_without:scan|max:200|mimes:pdf',
            'scan' => 'required_without:pdf|max:200|mimes:jpeg,jpg,png'
        ]);
        
        $validData['serial_no'] = 'CS/RM/' . now()->year.'/';
        
        if(Cache::has('Reminder')) {
            Cache::increment('Reminder');
        }
        else {
            Cache::put('Reminder', 1, 525600);    //1 year in miuntes
        }
        
        $serial_no_val = Cache::get('Reminder');
        $validData['serial_no'] .= str_pad($serial_no_val,4,'0',STR_PAD_LEFT);
    
        $pdf = request()->file('pdf');
        $scan = request()->file('scan');
        if($pdf)
            $validData['pdf'] = $pdf->store('letters/outgoing/reminders');
        if($scan)
            $validData['scan'] = $scan->store('letters/outgoing/reminders');
        
        LetterReminder::create($validData);

        return back();
    }

    public function update(LetterReminder $reminder)
    {
        $data = request()->validate([
            'pdf' => 'required_without:scan|max:200|mimes:pdf',
            'scan' => 'required_without:pdf|max:200|mimes:jpeg,jpg,png,pdf'
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
        $pdf = $reminder['pdf'];
        $scan = $reminder['scan'];

        if($pdf) 
        {
            Storage::delete($pdf);
        }
        if($scan)
        {
            Storage::delete($scan);
        }
        
        $reminder->delete();

        return back();
    }

}
