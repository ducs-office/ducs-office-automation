@extends('layouts.master')
@section('body')
    <div class="m-6 page-card pb-0">
        <div class="flex items-baseline px-6 pb-4 border-b">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letters</h1>
            <a href="/outgoing-letters/create" class="btn btn-magenta is-sm shadow-inset">
                New
            </a>
        </div>
        <letter-search-filters class="px-6 py-2 border-b"
            :filters="{{ json_encode([
                [ 'field' => 'date', 'label' => 'Before Date', 'type' => 'date', 'operator' => 'greater_than' ],
                [ 'field' => 'date', 'label' => 'After Date', 'type' => 'date', 'operator' => 'less_than' ],
                [ 'field' => 'type', 'label' => 'Type', 'type' => 'select', 'operator' => 'equals', 'options' => $types->toArray() ],
                [ 'field' => 'recipient', 'label' => 'Recipient', 'type' => 'select', 'operator' => 'equals', 'options' => $recipients->toArray() ],
                [ 'field' => 'sender', 'label' => 'Sender', 'type' => 'select', 'operator' => 'equals', 'options' => $senders->toArray() ],
                [ 'field' => 'creator', 'label' => 'Creator', 'type' => 'select', 'operator' => 'equals', 'options' => $creators->toArray() ],
            ]) }}"></letter-search-filters>
        @forelse($outgoing_letters as $letter)
            @include('outgoing_letters.partials.letter', compact('letter'))
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
