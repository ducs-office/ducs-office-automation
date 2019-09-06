<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutgoingLetterLogsController extends Controller
{
    public function create()
    {
        return view('outgoing_letter_logs.create');
    }
}
