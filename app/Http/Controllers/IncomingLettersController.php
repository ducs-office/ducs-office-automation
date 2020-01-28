<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handover;
use App\IncomingLetter;
use App\OutgoingLetter;
use Auth;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

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

        $incoming_letters = $query->orderBy('date', 'DESC')->get();

        $recipients = User::select('id', 'name')->whereIn(
            'id',
            IncomingLetter::selectRaw('DISTINCT(recipient_id)')
        )->get()->pluck('name', 'id');

        $senders = IncomingLetter::selectRaw('DISTINCT(sender)')->get()->pluck('sender', 'sender');

        $priorities = IncomingLetter::selectRaw('DISTINCT(priority)')->get()->pluck('priority', 'priority');

        $priorities[1] = 'High';
        $priorities[2] = 'Medium';
        $priorities[3] = 'Low';

        return view('staff.incoming_letters.index', compact(
            'incoming_letters',
            'recipients',
            'senders',
            'priorities'
        ));
    }

    public function create()
    {
        return view('staff.incoming_letters.create');
    }

    public function store()
    {
        $data = request()->validate([
            'date' => 'required|date|before_or_equal:today',
            'received_id' => 'required|string|min:3|max:190',
            'sender' => 'required|string|min:5|max:100',
            'recipient_id' => 'required|exists:users,id',
            'handovers' => 'nullable|array',
            'handovers.*' => 'integer|exists:users,id',
            'priority' => 'nullable|in:1,2,3',
            'subject' => 'required|string|min:5|max:100',
            'description' => 'nullable|string|min:4|max:400',
            'attachments' => 'required|array|min:1|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        $letter = IncomingLetter::create($data);

        if (isset($data['handovers'])) {
            $letter->handovers()->attach($data['handovers']);
        }

        $letter->attachments()->createMany(
            array_map(function ($attachedFile) {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/incoming')
                ];
            }, request()->file('attachments'))
        );

        return redirect('/incoming-letters');
    }

    public function edit(IncomingLetter $incoming_letter)
    {
        return view('staff.incoming_letters.edit', compact('incoming_letter'));
    }

    public function update(IncomingLetter $incoming_letter, Request $request)
    {
        $rules = [
            'date' => ['sometimes', 'required', 'date', 'before_or_equal:today'],
            'received_id' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'sender' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'recipient_id' => ['sometimes', 'required', 'exists:users,id'],
            'handovers' => ['sometimes', 'nullable', 'array'],
            'handovers.*' => ['integer', 'exists:users,id'],
            'priority' => ['nullable', 'in:1,2,3'],
            'subject' => ['sometimes', 'required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:400'],
            'attachments' => ['required', 'array', 'max:2'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ];

        if ($incoming_letter->attachments()->count() < 1) {
            array_push($rules['attachments'], 'min:1');
        } else {
            array_unshift($rules['attachments'], 'sometimes');
        }

        $validData = $request->validate($rules);

        $incoming_letter->update($validData);

        if ($request->has('handovers')) {
            $incoming_letter->handovers()->sync($validData['handovers']);
        }

        if ($request->hasFile('attachments')) {
            $incoming_letter->attachments()->createMany(
                array_map(function ($attachedFile) {
                    return [
                        'original_name' => $attachedFile->getClientOriginalName(),
                        'path' => $attachedFile->store('/letter_attachments/incoming')
                    ];
                }, $request->file('attachments'))
            );
        }

        return redirect('/incoming-letters');
    }

    public function destroy(IncomingLetter $incoming_letter)
    {
        $incoming_letter->attachments->each->delete();
        $incoming_letter->remarks->each->delete();

        $incoming_letter->delete();

        return redirect('/incoming-letters');
    }

    public function storeRemark(IncomingLetter $incoming_letter)
    {
        $data = request()->validate([
            'description'=>'required|string|min:2|max:190',
        ]);

        $incoming_letter->remarks()->create($data + ['user_id' => Auth::id()]);

        return back();
    }
}
