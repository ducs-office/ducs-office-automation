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
    protected function store(Request $request) {

      $outgoingletterlog = new OutgoingLetterLog;
      $outgoingletterlog->date = $request->date;
      $outgoingletterlog->type = $request->type;
      $outgoingletterlog->recipient = $request->recipient;
      $outgoingletterlog->sender_id = $request->sender_id;
      $outgoingletterlog->description = $request->description;
      $outgoingletterlog->amount = $request->amount;
      $outgoingletterlog->save();
    }
    
}
