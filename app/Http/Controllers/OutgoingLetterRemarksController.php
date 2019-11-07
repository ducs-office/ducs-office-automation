<?php

namespace App\Http\Controllers;

use App\Remark;
use App\OutgoingLetter;
use Auth;

class OutgoingLetterRemarksController extends Controller
{
    public function store(OutgoingLetter $outgoing_letter)
    {
        $this->authorize('create', [Remark::class, $outgoing_letter]);

        $data = request()->validate([
            'description'=>'required|min:10|max:255|string',
        ]);

        $outgoing_letter->remarks()->create($data + ['user_id' => Auth::id()]);

        return back();
    }
}
