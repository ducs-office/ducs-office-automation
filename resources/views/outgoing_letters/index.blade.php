@extends('layouts.master')
@section('body')
    <div class="m-6 page-card pb-2 overflow-x-auto">
        <div class="flex items-baseline px-6 pb-4 border-b mb-2">
            <h1 class="page-header mb-0 px-0 mr-4">Outgoing Letters</h1>
            <a href="/outgoing-letters/create" class="btn btn-magenta is-sm shadow-inset">
                Create
            </a>
        </div>
        
        <div class="flex justify-between items-end py-2 px-6 mb-2">
            <form method="GET" class="flex items-end">
                <input type="text" name="after" 
                    placeholder="After date"
                    class="form-input is-sm mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">

                <input type="text" name="before" 
                    placeholder="Before date"
                    class="form-input is-sm mr-4" 
                    onfocus="this.type='date'"
                    onblur="this.type='text'">
                    
                <button type="submit" class="btn btn-black is-sm">Apply Filter</button>
            </form>
        </div>
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
