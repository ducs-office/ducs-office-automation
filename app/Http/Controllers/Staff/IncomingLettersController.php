<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Staff\StoreIncomingLetterRequest;
use App\Http\Requests\Staff\UpdateIncomingLettersRequest;
use App\IncomingLetter;
use App\User;

class IncomingLettersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(IncomingLetter::class, 'incoming_letter');
    }

    public function index(Request $request)
    {
        $filters = $request->query('filters');

        $query = IncomingLetter::applyFilter($filters)->with(['remarks.user', 'handovers']);

        if ($request->has('search') && $request['search']!= '') {
            $query->where('subject', 'like', '%'.$request['search'].'%')
                    ->orWhere('description', 'like', '%'.$request['search'].'%');
        }

        $recipients = User::select('id', 'name')->whereIn(
            'id',
            IncomingLetter::selectRaw('DISTINCT(recipient_id)')
        )->get()->pluck('name', 'id');

        return view('staff.incoming_letters.index', [
            'incoming_letters' => $query->orderBy('date', 'DESC')->get(),
            'recipients' => $recipients,
            'senders' => IncomingLetter::selectRaw('DISTINCT(sender)')->get()->pluck('sender', 'sender'),
            'priorities' => config('options.incoming_letters.priorities'),
            'priority_colors' => config('options.incoming_letters.priority_colors')
        ]);
    }

    public function create()
    {
        return view('staff.incoming_letters.create', [
            'priorities' => config('options.incoming_letters.priorities'),
            'priority_colors' => config('options.incoming_letters.priority_colors')
        ]);
    }

    public function store(StoreIncomingLetterRequest $request)
    {
        $letter = IncomingLetter::create($request->validated());

        if ($request->has('handovers')) {
            $letter->handovers()->attach($request->handovers);
        }

        $letter->attachments()->createMany($request->attachmentFiles());

        return redirect(route('staff.incoming_letters.index'));
    }

    public function edit(IncomingLetter $incoming_letter)
    {
        return view('staff.incoming_letters.edit', [
            'incoming_letter' => $incoming_letter,
            'priorities' => config('options.incoming_letters.priorities'),
        ]);
    }

    public function update(UpdateIncomingLettersRequest $request, IncomingLetter $incoming_letter)
    {
        $incoming_letter->update($request->validated());

        $incoming_letter->handovers()->sync($request->handovers ?? []);

        $incoming_letter->attachments()->createMany(
            $request->attachmentFiles()
        );

        return redirect(route('staff.incoming_letters.index'));
    }

    public function destroy(IncomingLetter $incoming_letter)
    {
        $incoming_letter->attachments->each->delete();
        $incoming_letter->remarks->each->delete();

        $incoming_letter->delete();

        return redirect(route('staff.incoming_letters.index'));
    }

    public function storeRemark(Request $request, IncomingLetter $incoming_letter)
    {
        $data = $request->validate([
            'description'=>'required|string|min:2|max:190',
        ]);

        $incoming_letter->remarks()->create($data + ['user_id' => $request->user()->id]);

        return back();
    }
}
