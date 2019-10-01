@extends('layouts.master')
@section('body')
    <div class="m-6 page-card pb-2 overflow-x-auto">
        <div class="flex items-baseline px-6 pb-4 border-b mb-2">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letters</h1>
            <a href="/outgoing-letters/create" class="btn btn-magenta is-sm shadow-inset">
                Create
            </a>
            <button class="mr-2 btn btn-black is-sm ml-auto" @click="$modal.show('filters')">
                <feather-icon name="filter" class="h-4" stroke-width="2"></feather-icon>
            </button>
        </div>

        <modal name="filters" height="auto">
            <form method="GET" class="flex flex-wrap items-end p-4">
                <h3 class="w-full mb-8 text-xl">Filters</h3>
                <input type="text" name="search" class="form-input w-full mx-2 mb-2" placeholder="Search for letters...">
                <input type="text" name="after"
                    placeholder="After date"
                    class="form-input is-sm mx-2 my-2"
                    onfocus="this.type='date'"
                    onblur="this.type='text'">

                <input type="text" name="before"
                    placeholder="Before date"
                    class="form-input is-sm mx-2 my-2"
                    onfocus="this.type='date'"
                    onblur="this.type='text'">

                <select name="filters[recipient][equals]" id="recipient" class="form-input is-sm mx-2 my-2">
                    <option value="" selected>Recipient</option>
                    @foreach($recipients as $recipient)
                        <option value="{{ $recipient }}">{{ $recipient }}</option>
                    @endforeach
                </select>

                <select name="filters[type][equals]" id="type" class="form-input is-sm mx-2 my-2">
                    <option value="" selected>Type</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>

                <select name="filters[sender_id][equals]" id="type" class="form-input is-sm mx-2 my-2">
                    <option value="" selected>Sender</option>
                    @foreach ($senders as $sender)
                        <option value="{{ $sender['id'] }}">{{ $sender['name'] }}</option>
                    @endforeach
                </select>

                <select name="filters[creator_id][equals]" id="type" class="form-input is-sm m-2">
                    <option value="" selected>creator</option>
                    @foreach ($creators as $creator)
                        <option value="{{ $creator['id'] }}">{{ $creator['name'] }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-black is-sm m-2">Apply Filter</button>
            </form>
        </modal>

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
