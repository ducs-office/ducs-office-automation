<?php

namespace App\Http\Controllers\Staff;

use Auth;
use App\OutgoingLetter;
use App\Remark;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

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

    protected function store(Request $request)
    {
        $validData = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'type' => 'required|in:Bill,Notesheet,General',
            'recipient' => 'required|min:5|max:100',
            'sender_id' => 'required|integer|exists:users,id',
            'subject' => 'required|string|min:5|max:100',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'attachments' => 'required|array|min:1|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        $letter = OutgoingLetter::create($validData + ['creator_id' => Auth::id()]);

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

    public function update(OutgoingLetter $outgoing_letter, Request $request)
    {
        $rules = [
            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'recipient' => ['sometimes', 'required', 'min:5', 'max:100'],
            'subject' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
            'amount' => ['nullable', 'numeric'],
            'sender_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            'attachments' => ['required', 'array', 'max:2'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ];

        if ($outgoing_letter->attachments()->count() < 1) {
            array_push($rules['attachments'], 'min:1');
        } else {
            array_unshift($rules['attachments'], 'sometimes');
        }

        $validData = $request->validate($rules);

        $outgoing_letter->update($validData);

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
