<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreOutgoingLetterRequest;
use App\Http\Requests\Staff\UpdateOutgoingLetterRequest;
use App\Models\OutgoingLetter;
use App\Models\Remark;
use App\Models\User;
use App\Types\OutgoingLetterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingLettersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(OutgoingLetter::class, 'letter');
    }

    public function index(Request $request)
    {
        $filters = $request->query('filters');

        $query = OutgoingLetter::applyFilter($filters)->with(['remarks.user', 'reminders']);

        if ($request->has('search') && request('search') !== '') {
            $query->where('subject', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }

        return view('staff.outgoing_letters.index', [
            'letters' => $query->orderBy('date', 'DESC')->paginate(),
            'types' => collect(OutgoingLetterType::values())->combine(OutgoingLetterType::values()),
            'recipients' => OutgoingLetter::selectRaw('DISTINCT(recipient)')->get()->pluck('recipient', 'recipient'),
            'creators' => User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(creator_id)'))->get()->pluck('name', 'id'),
            'senders' => User::select('id', 'name')->whereIn('id', OutgoingLetter::selectRaw('DISTINCT(sender_id)'))->get()->pluck('name', 'id'),
        ]);
    }

    public function create()
    {
        return view('staff.outgoing_letters.create', [
            'types' => OutgoingLetterType::values(),
        ]);
    }

    public function store(StoreOutgoingLetterRequest $request)
    {
        $data = $request->validated();

        $letter = OutgoingLetter::create($data + ['creator_id' => $request->user()->id]);

        $letter->attachments()->createMany(
            array_map(static function ($attachedFile) {
                return [
                    'original_name' => $attachedFile->getClientOriginalName(),
                    'path' => $attachedFile->store('/letter_attachments/outgoing'),
                ];
            }, $request->file('attachments'))
        );

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function edit(OutgoingLetter $letter)
    {
        return view('staff.outgoing_letters.edit', ['letter' => $letter]);
    }

    public function update(UpdateOutgoingLetterRequest $request, OutgoingLetter $letter)
    {
        $letter->update($request->validated());

        if ($request->hasFile('attachments')) {
            $letter->attachments()->createMany(
                array_map(static function ($attachedFile) {
                    return [
                        'original_name' => $attachedFile->getClientOriginalName(),
                        'path' => $attachedFile->store('/letter_attachments/outgoing'),
                    ];
                }, $request->file('attachments'))
            );
        }

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function destroy(OutgoingLetter $letter)
    {
        $letter->reminders->each->delete();
        $letter->remarks->each->delete();
        $letter->attachments->each->delete();

        $letter->delete();

        return redirect(route('staff.outgoing_letters.index'));
    }

    public function storeRemark(OutgoingLetter $letter)
    {
        $this->authorize('create', Remark::class, $letter);

        $data = request()->validate([
            'description' => 'required|string|min:2|max:190',
        ]);

        $letter->remarks()->create($data + ['user_id' => Auth::id()]);

        return back();
    }
}
