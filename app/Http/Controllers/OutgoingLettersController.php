<?php

namespace App\Http\Controllers;

use Auth;
use App\OutgoingLetter;
use App\Remark;
use App\User;
use Illuminate\Http\Request;
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

        $outgoing_letters = $query->orderBy('date', 'DESC')->get();

        $recipients = OutgoingLetter::selectRaw('DISTINCT(recipient)')->get()->pluck('recipient', 'recipient');
        $types = OutgoingLetter::selectRaw('DISTINCT(type)')->get()->pluck('type', 'type');
        $senders = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(sender_id)'))->get()->pluck('name', 'id');
        $creators = User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(creator_id)'))->get()->pluck('name', 'id');


        return view('outgoing_letters.index', compact(
            'outgoing_letters',
            'types',
            'recipients',
            'creators',
            'senders'
        ));
    }

    public function create()
    {
        return view('outgoing_letters.create');
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

        return redirect('/outgoing-letters');
    }

    public function edit(OutgoingLetter $outgoing_letter)
    {
        return view('outgoing_letters.edit', compact('outgoing_letter'));
    }

    public function update(OutgoingLetter $outgoing_letter, Request $request)
    {
        $validData = $request->validate([
            'date' => 'sometimes|required|date|before_or_equal:today',
            'recipient' =>  'sometimes|required|min:5|max:100',
            'subject' => 'sometimes|required|string|min:5|max:100',
            'description' => 'nullable|string|max:400',
            'amount' => 'nullable|numeric',
            'sender_id' => 'sometimes|required|integer|exists:users,id',
            'attachments' => 'sometimes|required|array|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);

        if (isset($validData['date'])) {
            $year = $outgoing_letter->date->format('Y');
            $update_date = new Carbon($validData['date']);
            $update_year = $update_date->format('Y');
            if ($year != $update_year) {
                $prefixes = [
                    'Bill' => 'TR/',
                    'Notesheet' => 'NTS/',
                    'General' => ''
                ];

                $serial_no = "CS/{$prefixes[$outgoing_letter->type]}{$update_year}";
                $cache_key = "letter_seq_{$serial_no}";
                $number_sequence = str_pad(Cache::increment($cache_key), 4, '0', STR_PAD_LEFT);

                $outgoing_letter->serial_no = "$serial_no/$number_sequence";
            }
        }

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

        return redirect('/outgoing-letters');
    }

    public function destroy(OutgoingLetter $outgoing_letter)
    {
        $outgoing_letter->reminders->each->delete();
        $outgoing_letter->remarks->each->delete();
        $outgoing_letter->attachments->each->delete();

        $outgoing_letter->delete();

        return redirect('/outgoing-letters');
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
