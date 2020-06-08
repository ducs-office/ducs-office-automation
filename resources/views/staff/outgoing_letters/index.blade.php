@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="flex items-baseline mb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letters</h1>
            @can('create', \App\Models\OutgoingLetter::class)
            <a href="{{ route('staff.outgoing_letters.create') }}" class="btn btn-magenta is-sm shadow-inset">
                New
            </a>
            @endcan
            @include('staff.partials.letter-filters', [
                'filters' => [
                    [ 'name' => 'before_date', 'label' => 'Before Date', 'type' => 'date' ],
                    [ 'name' => 'after_date', 'label' => 'After Date', 'type' => 'date' ],
                    [ 'name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => $types->toArray() ],
                    [ 'name' => 'recipient', 'label' => 'Recipient', 'type' => 'select', 'options' => $recipients->toArray() ],
                    [ 'name' => 'sender_id', 'label' => 'Sender', 'type' => 'select', 'options' => $senders->toArray() ],
                    [ 'name' => 'creator', 'label' => 'Creator', 'type' => 'select', 'options' => $creators->toArray() ],
                ]
            ])
        </div>
        <p class="mb-4">
            Displaying <b>{{ $letters->firstItem() }}</b> - <b>{{ $letters->lastItem() }}</b> out of <b>{{ $letters->total() }}</b>
        </p>
        <div class="space-y-5">
            @forelse($letters as $letter)
                @include('staff.outgoing_letters.partials.letter', [
                    'letter' => $letter
                ])
            @empty
                <div class="py-8 flex flex-col items-center justify-center text-gray-500">
                    <x-feather-icon name="frown" class="h-16"></x-feather-icon>
                    <p class="mt-4 mb-2  font-bold">
                        Sorry! No Letters {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
                    </p>
                </div>
            @endforelse
        </div>
        <div class="my-6">
            {{ $letters->links()  }}
        </div>
    </div>
    <form id="remove-attachment" method="POST" onsubmit="return confirm('Do you really want to delete attachment?');">
        @csrf_token @method('DELETE')
    </form>
@endsection
