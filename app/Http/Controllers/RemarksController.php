<?php

namespace App\Http\Controllers;

use App\IncomingLetter;
use Illuminate\Http\Request;
use App\OutgoingLetter;
use App\Remark;
use Illuminate\Support\Facades\Auth;

class RemarksController extends Controller
{
    public function update(Remark $remark)
    {
        $this->authorize('update', $remark);

        $remark->update(request()->validate([
            'description'=>'required|min:10|max:255|string'
        ]));
        return back();
    }

    public function destroy(Remark $remark)
    {
        $this->authorize('delete', $remark);

        $remark->delete();

        return back();
    }
}
