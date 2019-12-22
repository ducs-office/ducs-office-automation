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
            
        return view('incoming_letters.index', compact(
            'incoming_letters',
            'recipients',
            'senders',
            'priorities'
        ));
    }

    public function create()
    {
        return view('incoming_letters.create');
    }

    public function store()
    {
        $data = request()->validate([
            'date' => 'required|date|before_or_equal:today',
            'received_id' => 'required|string',
            'sender' => 'required|string|max:50',
            'recipient_id' => 'required|exists:users,id',
            'handovers' => 'nullable|array',
            'handovers.*' => 'exists:users,id',
            'priority' => 'nullable|in:1,2,3',
            'subject' => 'required|string|max:80',
            'description' => 'nullable|string|max:400',
            'attachments' => 'required|array|max:1',
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
        return view('incoming_letters.edit', compact('incoming_letter'));
    }

    public function update(IncomingLetter $incoming_letter, Request $request)
    {
        $validData = $request->validate([
            'date' => 'sometimes|required|date|before_or_equal:today',
            'received_id' => 'sometimes|required|string',
            'sender' => 'sometimes|required|string',
            'recipient_id' => 'sometimes|required|exists:users,id',
            'handovers' => 'sometimes|nullable|array',
            'handovers.*' => 'exists:users,id',
            'priority' => 'nullable|in:1,2,3',
            'subject' => 'sometimes|required|string|max:80',
            'description' => 'nullable|string|max:400',
            'attachments' => 'sometimes|required|array|max:2',
            'attachments.*' => 'file|max:200|mimes:jpeg,jpg,png,pdf'
        ]);
        
        if (isset($validData['date'])) {
            $year = $incoming_letter->date->format('Y');
            $update_date = new Carbon($validData['date']);
            $update_year = $update_date->format('Y');
            if ($year != $update_year) {
                $seq_id = "CS/D/{$update_year}";
                $cache_key = "letter_seq_{$seq_id}";
                $number_seq = str_pad(Cache::increment($cache_key), 4, "0", STR_PAD_LEFT);
                $incoming_letter->serial_no = "$seq_id/$number_seq";
            }
        }

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
            'description'=>'required|min:10|max:255|string',
        ]);

        $incoming_letter->remarks()->create($data + ['user_id' => Auth::id()]);
        
        return back();
    }
}
