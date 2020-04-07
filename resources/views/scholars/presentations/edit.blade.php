@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Updae Presentation</h2>
        </div>
        <form action="{{ route('scholars.profile.presentation.update', $presentation)}}" method="post" class="px-6">
            @csrf_token
            <div class="mb-4">
                <label for="date" class="form-label block mb-1">
                    Date <span class="text-red-600">*</span>
                </label>
                <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{old('date', $presentation->date)}}">
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="city" class="form-label block mb-1">
                        City <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="city" class="form-input w-full {{ $errors->has('city') ? ' border-red-600' : ''}}"
                    value="{{old('city', $presentation->city)}}">
                </div>`
                <div class="ml-4 w-1/2">
                    <label for="country"` class="form-label block mb-1">
                        Country 
                    </label>
                    <input type="text" name="country" class="form-input w-full {{ $errors->has('country') ? ' border-red-600' : ''}}"
                    value="{{old('country', $presentation->country)}}">
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="date" class="form-label block mb-1">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                    value="{{old('date', $presentation->date)}}">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="venue" class="block form-label flex-1">Venue:</label>
                    <select id="venue" name="venue" class="block form-input flex-1 w-full">
                        <option value="" selected> Choose the venue </option>
                        @foreach ($venues as $acronym => $venue)
                        <option value=" {{ $acronym }}" {{ old('venue', $presentation->venue) === $acronym ? 'selected': ''}}>
                            {{ $venue }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="scopus_indexed" class="mr-2" value="{{old('value', $presentation->value)}}">
                <label for="scopus_indexed" class="w-full form-label">
                Scopus Indexed ?
                </label>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
    </div>
@endsection