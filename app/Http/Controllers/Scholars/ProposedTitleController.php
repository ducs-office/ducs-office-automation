<?php

namespace App\Http\Controllers\Scholars;

use App\Http\Controllers\Controller;
use App\Models\Scholar;
use Illuminate\Http\Request;

class ProposedTitleController extends Controller
{
    public function update(Request $request, Scholar $scholar)
    {
        abort_unless(
            auth()->id() == $scholar->id
            && (
                $scholar->currentPhdSeminarAppeal() === null
                || $scholar->currentPhdSeminarAppeal()->isRejected()
            ),
            401
        );

        $request->validate(['proposed_title' => ['required', 'string']]);

        $scholar->update([
            'proposed_title' => $request->proposed_title,
        ]);

        flash('Proposed Title for Seminar updated successfully!')->success();

        return redirect()->back();
    }
}
