<?php

namespace App\Http\Controllers\Staff;

use App\IncomingLetter;
use Illuminate\Http\Request;
use App\OutgoingLetter;
use App\Remark;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class RemarksController extends Controller
{
    public function update(Remark $remark)
    {
        $this->authorize('update', $remark);

        $remark->update(request()->validate([
            'description'=>'required|string|min:2|max:190'
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
