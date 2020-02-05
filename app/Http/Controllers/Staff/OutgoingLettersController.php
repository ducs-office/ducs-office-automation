<?php

namespace App\Http\Controllers\Staff;

use Auth;
use App\OutgoingLetter;
use App\Remark;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreOutgoingLetterRequest;
use App\Http\Requests\Staff\UpdateOutgoingLetterRequest;

class OutgoingLettersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(OutgoingLetter::class, 'outgoing_letter');
    }

    public function index(Request $request)
    {
        $filters = $request->query('filters');

        $query = OutgoingLetter::applyFilter($filters)->with(['remarks.user', 'reminders']);

        if ($request->has('search') && request('search')!= '') {
            $query->where('subject', 'like', '%'.request('search').'%')
                ->orWhere('description', 'like', '%'.request('search').'%');
        }

        return view('staff.outgoing_letters.index', [
            'outgoing_letters' => $query->orderBy('date', 'DESC')->get(),
            'types' => OutgoingLetter::selectRaw('DISTINCT(type)')->get()->pluck('type', 'type'),
            'recipients' => OutgoingLetter::selectRaw('DISTINCT(recipient)')->get()->pluck('recipient', 'recipient'),
            'creators' => User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(creator_id)'))->get()->pluck('name', 'id'),
            'senders' => User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(sender_id)'))->get()->pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('staff.outgoing_letters.create');
    }

    public function store(StoreOutgoingLetterRequest $request)
    {
        $data = $request->validated();

        $letter = OutgoingLetter::create($data + ['creator_id' => $request->user()->id]);

        $letter->attachments()->createMany(
            array_map(function ($attachedFile) {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/outgoing')
                ];
            }, $request->file('attachments'))
        );

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function edit(OutgoingLetter $outgoing_letter)
    {
        return view('staff.outgoing_letters.edit', [
            'outgoing_letter' => $outgoing_letter,
        ]);
    }

    public function update(UpdateOutgoingLetterRequest $request, OutgoingLetter $outgoing_letter)
    {
        $outgoing_letter->update($request->validated());

        if ($request->hasFile('attachments')) {
            $outgoing_letter->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'original_name' => $attachedFile->getClientOriginalName(),
                        'path' => $attachedFile->store('/letter_attachments/outgoing')
                    ];
                }, $request->file('attachments'))
            );
        }

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function destroy(OutgoingLetter $outgoing_letter)
    {
        $outgoing_letter->reminders->each->delete();
        $outgoing_letter->remarks->each->delete();
        $outgoing_letter->attachments->each->delete();

        $outgoing_letter->delete();

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function storeRemark(OutgoingLetter $outgoing_letter)
    {
        $this->authorize('create', Remark::class, $outgoing_letter);

        $data = request()->validate([
            'description'=>'required|string|min:2|max:190',
        ]);

        $outgoing_letter->remarks()->create($data + ['user_id' => Auth::id()]);

        return back();
    }
}
