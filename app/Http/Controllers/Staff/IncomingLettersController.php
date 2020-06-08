<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreIncomingLetterRequest;
use App\Http\Requests\Staff\UpdateIncomingLettersRequest;
use App\Models\IncomingLetter;
use App\Models\User;
use App\Types\Priority;
use Illuminate\Http\Request;

class IncomingLettersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(IncomingLetter::class, 'letter');
    }

    public function index()
    {
        $letters = IncomingLetter::query()
            ->filter()
            ->with(['remarks.user', 'handovers'])
            ->orderBy('date', 'DESC')
            ->paginate();

        return view('staff.incoming_letters.index', [
            'letters' => $letters,
            'recipients' => User::select(['id', 'first_name', 'last_name'])
                    ->whereIn('id', IncomingLetter::selectRaw('DISTINCT(recipient_id)'))
                    ->get()->pluck('name', 'id'),
            'senders' => IncomingLetter::selectRaw('DISTINCT(sender)')->get()->pluck('sender', 'sender'),
            'priorities' => array_combine(Priority::values(), Priority::values()),
        ]);
    }

    public function create()
    {
        return view('staff.incoming_letters.create', [
            'priorities' => Priority::values(),
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
            'priorities' => Priority::values(),
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
