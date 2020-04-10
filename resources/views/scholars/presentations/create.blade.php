@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Create Presentation</h2>
        </div>
        <form action="{{ route('scholars.profile.presentation.store')}}" method="post" class="px-6"
            onsubmit="
                if(! scopus_indexed.checked) {
                    alert('You cannot create a presentation which is not scopus indexed!');
                    return false;
                }
                return true;
            ">
            @csrf_token
            <div class="mb-4">
                <label for="publication" class="form-label block mb-1">
                    Publication <span class="text-red-600">*</span>
                </label>
                <select name="publication_id" class="block form-input w-full {{ $errors->has('publication_id') ? ' border-red-600' : ''}}">
                    <option value="" class="text-gray-600 " selected> Select Publication </option>
                    @foreach ($publications as $publication)
                        <option value=" {{ $publication->id }} "
                            {{ $publication->id == old('publication_id') ? 'selected':''}} >
                            {{ $publication->paper_title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="event_type" class="form-label block mb-1">Event Type <span class="text-red-600">*</span> </label>
                <select name="event_type" class="block form-input w-full {{ $errors->has('event_type') ? ' border-red-600' : ''}}"> 
                    <option value="" class="text-gray-600" selected>Select Event Type </option>
                    @foreach ($eventTypes as $acronym => $eventType)
                    <option value=" {{ $acronym }}" class="text-gray-600"
                        {{ old('event_type') === $acronym ? 'selected': ''}}>
                        {{ $eventType }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="event_name" class="form-label block mb-1">
                    Event Name <span class="text-red-600">*</span>
                </label>
                <input type="text" name="event_name" class="form-input w-full {{ $errors->has('event_name') ? ' border-red-600' : ''}}"
                placeholder="Enter Event Name" value="{{old('event_name')}}">
            </div>
            <div class="mb-4">
                <label for="date" class="form-label block mb-1">
                    Date <span class="text-red-600">*</span>
                </label>
                <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{old('date')}}">
            </div>
            <div class="mb-4 flex">
                <div class="w-1/2">
                    <label for="city" class="form-label block mb-1">
                        City <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="city" class="form-input w-full {{ $errors->has('city') ? ' border-red-600' : ''}}" placeholder="Enter City"
                    value="{{old('city')}}">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="country" class="form-label block mb-1">
                        Country <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="country" class="form-input w-full {{ $errors->has('country') ? ' border-red-600' : ''}}" placeholder="Enter Country"
                    value="{{old('country')}}">
                </div>
            </div>
            <div class="mb-4 mt-2 flex items-center pt-2">
                <input type="checkbox" name="scopus_indexed"  id ="scopus_indexed" class="mr-2"
                value="1" {{old('scopus_indexed') === "1" ? 'checked': ''}}>
                <label for="scopus_indexed" class="w-full form-label">
                    Scopus Indexed ?
                </label>
            </div>
            <div class="mt-6">
                <input type="submit" class="w-full btn btn-magenta" value="Create">
            </div>
        </form>
    </div>
@endsection