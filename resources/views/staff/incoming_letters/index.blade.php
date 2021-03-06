@extends('layouts.master')
@section('body')
    <div class="flex items-baseline px-2 mb-4">
        <h1 class="text-2xl font-bold mr-4">Incoming Letters</h1>
        @can('create', \App\Models\IncomingLetter::class)
            <a href="{{ route('staff.incoming_letters.create') }}"
                class="btn btn-magenta is-sm shadow-inset">
                New
            </a>
        @endcan
        @include('_partials.filters', [
            'filters' => [
                ['name' => 'before_date', 'label' => 'Before Date', 'type' => 'date'],
                ['name' => 'after_date', 'label' => 'After Date', 'type' => 'date'],
                ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'options' => $priorities ],
                ['name' => 'recipient_id', 'label' => 'Recipient', 'type' => 'select', 'options' => $recipients->toArray() ],
                ['name' => 'sender', 'label' => 'Sender', 'type' => 'select', 'options' => $senders->toArray() ],
            ]
        ])
    </div>
    <p class="px-2 mb-4 text-right">
        Displaying <b>{{ $letters->firstItem() }}</b> - <b>{{ $letters->lastItem() }}</b> out of
        <b>{{ $letters->total() }}</b>
    </p>
    <div class="space-y-5">
        @forelse($letters as $letter)
            @include('_partials.incoming-letter')
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
    <form id="remove-attachment" method="POST" onsubmit="return confirm('Do you really want to delete attachment?');">
        @csrf_token @method('DELETE')
    </form>
@endsection
