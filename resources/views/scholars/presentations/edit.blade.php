@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Update Presentation</h2>
        </div>
        <form action="{{ route('scholars.profile.presentation.update', $presentation)}}" method="post" class="px-6">
            @csrf_token
            @method('PATCH')
            <div class="mb-4">
                <label for="publication_id" class="form-label block mb-1">
                    Publication <span class="text-red-600">*</span>
                </label>
                <select name="publication_id" class="block form-input w-full {{ $errors->has('publication_id') ? ' border-red-600' : ''}}">
                    <option value="" class="text-gray-600 " selected> Select your publication </option>
                    @foreach ($publications as $publication)
                        <option value=" {{ $publication->id }} " class="block w-full"
                            {{ $publication->id == old('publication_id', $presentation->publication_id) ? 'selected':''}} >
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
                        {{ old('event_type', $presentation->event_type) === $acronym ? 'selected': ''}}>
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
                placeholder="Enter Event Name" value="{{old('event_name', $presentation->event_name)}}">
            </div>
            <div class="mb-4">
                <label for="date" class="form-label block mb-1">
                    Date <span class="text-red-600">*</span>
                </label>
                <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{old('date', $presentation->date->format('Y-m-d'))}}">
            </div>
            <div class="mb-4 flex">
                <div class="w-1/2">
                    <label for="city" class="form-label block mb-1">
                        City <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="city" class="form-input w-full {{ $errors->has('city') ? ' border-red-600' : ''}}"
                    value="{{old('city', $presentation->city)}}">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="country" class="form-label block mb-1">
                        Country <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="country" class="form-input w-full {{ $errors->has('country') ? ' border-red-600' : ''}}"
                    value="{{old('country', $presentation->country)}}">
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
    </div>
@endsection