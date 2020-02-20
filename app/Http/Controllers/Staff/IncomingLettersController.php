<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreIncomingLetterRequest;
use App\Http\Requests\Staff\UpdateIncomingLettersRequest;
use App\IncomingLetter;
use App\User;
use Illuminate\Http\Request;

class IncomingLettersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(IncomingLetter::class, 'letter');
    }

    public function index(Request $request)
    {
        $filters = $request->query('filters');

        $query = IncomingLetter::applyFilter($filters)->with(['remarks.user', 'handovers']);

        if ($request->has('search') && $request['search'] !== '') {
            $query->where('subject', 'like', '%' . $request['search'] . '%')
                ->orWhere('description', 'like', '%' . $request['search'] . '%');
        }

        $recipients = User::select('id', 'name')->whereIn(
            'id',
            IncomingLetter::selectRaw('DISTINCT(recipient_id)')
        )->get()->pluck('name', 'id');

        return view('staff.incoming_letters.index', [
            'incomingLetters' => $query->orderBy('date', 'DESC')->get(),
            'recipients' => $recipients,
            'senders' => IncomingLetter::selectRaw('DISTINCT(sender)')->get()->pluck('sender', 'sender'),
            'priorities' => config('options.incoming_letters.priorities'),
            'priorityColors' => config('options.incoming_letters.priority_colors'),
        ]);
    }

    public function create()
    {
        return view('staff.incoming_letters.create', [
            'priorities' => config('options.incoming_letters.priorities'),
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

    public function edit(IncomingLetter $letter)
    {
        return view('staff.incoming_letters.edit', [
            'letter' => $letter,
            'priorities' => config('options.incoming_letters.priorities'),
        ]);
    }

    public function update(UpdateIncomingLettersRequest $request, IncomingLetter $letter)
    {
        $letter->update($request->validated());

        $letter->handovers()->sync($request->handovers ?? []);

        $letter->attachments()->createMany(
            $request->attachmentFiles()
        );

        return redirect(route('staff.incoming_letters.index'));
    }

    public function destroy(IncomingLetter $letter)
    {
        $letter->attachments->each->delete();
        $letter->remarks->each->delete();

        $letter->delete();

        return redirect(route('staff.incoming_letters.index'));
    }

    public function storeRemark(Request $request, IncomingLetter $letter)
    {
        $data = $request->validate([
            'description' => 'required|string|min:2|max:190',
        ]);

        $letter->remarks()->create($data + ['user_id' => $request->user()->id]);

        return back();
    }
}
