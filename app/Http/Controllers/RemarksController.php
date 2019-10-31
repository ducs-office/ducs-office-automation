<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OutgoingLetter;
use App\Remark;
use Illuminate\Support\Facades\Auth;

class RemarksController extends Controller
{

    public function store()
    {
        // dd(request());
        $data = request()->validate([
            'description'=>'required|min:10|max:255|string',
            'remarkable_id' => 'required|integer|exists:outgoing_letters,id',
            'remarkable_type' => 'required'
        ]);
        Remark::create($data + ['user_id' => Auth::id()]);
        
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
