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
                    Publication
                </label>
                <select name="publication_id" class="block form-input w-full">
                    <option value="" class="text-gray-600 " selected> Select your publication </option>
                    @foreach ($publications as $publication)
                        <option value=" {{ $publication->id }} "
                            {{ $publication->id === old('publication_id', $presentation->publication_id) ? 'selected':''}} >
                            {{ $publication->paper_title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="city" class="form-label block mb-1">
                    City 
                </label>
                <input type="text" name="city" class="form-input w-full {{ $errors->has('city') ? ' border-red-600' : ''}}"
                value="{{old('city', $presentation->city)}}">
            </div>
            <div class="mb-4">
                <label for="country" class="form-label block mb-1">
                    Country 
                </label>
                <input type="text" name="country" class="form-input w-full {{ $errors->has('country') ? ' border-red-600' : ''}}"
                value="{{old('country', $presentation->country)}}">
            </div>
            <div class="mb-4">
                <label for="date" class="form-label block mb-1">
                    Date 
                </label>
                <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{old('date', $presentation->date->format('Y-m-d'))}}">
            </div>
            <div class="mb-4">
                <label for="venue" class=" form-label">Venue:</label>
                @foreach ($venues as $acronym => $venue)
                <div class="flex my-2">
                    <input type="radio"  name="venue" value=" {{ $acronym }}"
                        {{ old('venue', $presentation->venue) === $acronym ? 'checked': ''}}>
                    <label for="{{ $acronym }}" class="ml-2 form-label is-sm "> {{ $venue }} </label>
                </div>
                @endforeach
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
    </div>
@endsection