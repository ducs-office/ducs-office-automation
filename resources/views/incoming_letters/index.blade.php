@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="flex items-baseline px-4 mb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Incoming Letters</h1>
            <a href="/incoming-letters/create" class="btn btn-magenta is-sm shadow-inset">
                New
            </a>
            <letter-search-filters class="ml-auto"
                :filters="{{ json_encode([
                [ 'field' => 'date', 'label' => 'Before Date', 'type' => 'date', 'operator' => 'greater_than' ],
                [ 'field' => 'date', 'label' => 'After Date', 'type' => 'date', 'operator' => 'less_than' ],
                [ 'field' => 'priority', 'label' => 'Priority', 'type' => 'select', 'operator' => 'equals', 'options' => $priorities->toArray() ],
                [ 'field' => 'recipient', 'label' => 'Recipient', 'type' => 'select', 'operator' => 'equals', 'options' => $recipients->toArray() ],
                [ 'field' => 'handover', 'label' => 'handover', 'type' => 'select', 'operator' => 'equals', 'options' => $handovers->toArray() ],
                [ 'field' => 'sender', 'label' => 'Sender', 'type' => 'select', 'operator' => 'equals', 'options' => $senders->toArray() ],
            ]) }}"></letter-search-filters>
        </div>
        @forelse($incoming_letters as $letter)
            @include('incoming_letters.partials.letter', compact('letter'))
        @empty
            <div class="py-8 flex flex-col items-center justify-center text-gray-500">
                <feather-icon name="frown" class="h-16"></feather-icon>
                <p class="mt-4 mb-2  font-bold">
                    Sorry! No Letters {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
                </p>
            </div>
        @endforelse
    </div>
@endsection
