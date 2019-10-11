<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OutgoingLetter;
use App\Remark;

class RemarksController extends Controller
{

    public function store()
    {
        $data = request()->validate([
            'description'=>'required|min:10|max:255|string',
            'letter_id' => 'required|integer|exists:outgoing_letters,id'
        ]);

        Remark::create($data);
        
        return back();
    }

    public function update(Remark $remark)
    {
        
        $remark->update(request()->validate([
            'description'=>'required|min:10|max:255|string' 
        ]));
        return back();
    }

    public function delete(Remark $remark)
    {
        $remark->delete();
        
        return back();
    }
}
