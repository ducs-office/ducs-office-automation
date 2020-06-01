@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="flex items-baseline px-4 mb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Incoming Letters</h1>
            @can('create', \App\Models\IncomingLetter::class)
                <a href="{{ route('staff.incoming_letters.create') }}" class="btn btn-magenta is-sm shadow-inset">
                    New
                </a>
            @endcan
            @include('staff.partials.letter-filters', [
                'filters' => [
                    [ 'field' => 'date', 'label' => 'Before Date', 'type' => 'date', 'operator' => 'greater_than' ],
                    [ 'field' => 'date', 'label' => 'After Date', 'type' => 'date', 'operator' => 'less_than' ],
                    [ 'field' => 'priority', 'label' => 'Priority', 'type' => 'select', 'operator' => 'equals', 'options' => $priorities ],
                    [ 'field' => 'recipient_id', 'label' => 'Recipient', 'type' => 'select', 'operator' => 'equals', 'options' => $recipients->toArray() ],
                    [ 'field' => 'sender', 'label' => 'Sender', 'type' => 'select', 'operator' => 'equals', 'options' => $senders->toArray() ],
                ]
            ])
        </div>
        <div class="space-y-5">
            @forelse($incomingLetters as $letter)
                @include('staff.incoming_letters.partials.letter',[
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
    </div>
    <form id="remove-attachment" method="POST" onsubmit="return confirm('Do you really want to delete attachment?');">
        @csrf_token @method('DELETE')
    </form>
@endsection
