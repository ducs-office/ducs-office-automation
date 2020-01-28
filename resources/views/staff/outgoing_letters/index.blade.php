@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="flex items-baseline px-4 mb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letters</h1>
            @can('create', 'App\OutgoingLetter')
            <a href="{{ route('outgoing_letters.create') }}" class="btn btn-magenta is-sm shadow-inset">
                New
            </a>
            @endcan
            @include('staff.partials.letter-filters', [
                'filters' => [
                    [ 'field' => 'date', 'label' => 'Before Date', 'type' => 'date', 'operator' => 'greater_than' ],
                    [ 'field' => 'date', 'label' => 'After Date', 'type' => 'date', 'operator' => 'less_than' ],
                    [ 'field' => 'type', 'label' => 'Type', 'type' => 'select', 'operator' => 'equals', 'options' => $types->toArray() ],
                    [ 'field' => 'recipient', 'label' => 'Recipient', 'type' => 'select', 'operator' => 'equals', 'options' => $recipients->toArray() ],
                    [ 'field' => 'sender_id', 'label' => 'Sender', 'type' => 'select', 'operator' => 'equals', 'options' => $senders->toArray() ],
                    [ 'field' => 'creator', 'label' => 'Creator', 'type' => 'select', 'operator' => 'equals', 'options' => $creators->toArray() ],
                ]
            ])

        </div>
        @forelse($outgoing_letters as $letter)
            @include('staff.outgoing_letters.partials.letter', [
                'letter' => $letter,
                'type_colors' => [
                    'General' => 'bg-magenta-700',
                    'Notesheet' => 'bg-teal-600',
                    'Bill' => 'bg-blue-600'
                ]
            ])
        @empty
            <div class="py-8 flex flex-col items-center justify-center text-gray-500">
                <feather-icon name="frown" class="h-16"></feather-icon>
                <p class="mt-4 mb-2  font-bold">
                    Sorry! No Letters {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
                </p>
            </div>
        @endforelse
    </div>
    <form id="remove-attachment" method="POST" onsubmit="return confirm('Do you really want to delete attachment?');">
        @csrf_token @method('DELETE')
    </form>
@endsection
