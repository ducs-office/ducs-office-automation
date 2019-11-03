<?php

namespace App\Http\Controllers;

use App\IncomingLetter;
use Illuminate\Http\Request;
use App\OutgoingLetter;
use App\Remark;
use Illuminate\Support\Facades\Auth;

class RemarksController extends Controller
{
    // public function storeIncoming(IncomingLetter $incoming_letter)
    // {
    //     $data = request()->validate([
    //         'description'=>'required|min:10|max:255|string',
    //     ]);

    //     $incoming_letter->remarks()->create($data + ['user_id' => Auth::id()]);
        
    //     return back();
    // }

    // public function store(OutgoingLetter $outgoing_letter)
    // {
    //     $data = request()->validate([
    //         'description'=>'required|min:10|max:255|string',
    //     ]);

    //     $outgoing_letter->remarks()->create($data + ['user_id' => Auth::id()]);
        
    //     return back();
    // }

    public function store(OutgoingLetter $outgoing_letter = null, IncomingLetter $incoming_letter = null)
    {
        $data = request()->validate([
            'description'=>'required|min:10|max:255|string',
        ]);

        if($outgoing_letter)
            $outgoing_letter->remarks()->create($data + ['user_id' => Auth::id()]);
        else
            $incoming_letter->remarks()->create($data + ['user_id' => Auth::id()]);
        
        return back();
    }

    public function update(Remark $remark)
    {
        
        $remark->update(request()->validate([
            'description'=>'required|min:10|max:255|string' 
        ]));
        return back();
    }

    public function destroy(Remark $remark)
    {
        $remark->delete();
        
        return back();
    }
}
