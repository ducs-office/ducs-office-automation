@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Create Presentation</h2>
        </div>
        <form action="{{ route('scholars.profile.presentation.store')}}" method="post" class="px-6">
            @csrf_token
            <div class="mb-4">
                <label for="publication" class="form-label block mb-1">
                    Publication
                </label>
                <select name="publication_id" class="block form-input w-full">
                    <option value="" class="text-gray-600 " selected> Select your publication </option>
                    @foreach ($publications as $publication)
                        <option value=" {{ $publication->id }} "
                            {{ $publication->id === old('publication_id') ? 'selected':''}} >
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
                value="{{old('city')}}">
            </div>
            <div class="mb-4">
                <label for="country" class="form-label block mb-1">
                    Country 
                </label>
                <input type="text" name="country" class="form-input w-full {{ $errors->has('country') ? ' border-red-600' : ''}}"
                value="{{old('country')}}">
            </div>
            <div class="mb-4">
                <label for="date" class="form-label block mb-1">
                    Date
                </label>
                <input type="date" name="date" class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}"
                value="{{old('date')}}">
            </div>
            <div class="mb-4">
                <label for="venue" class="form-label">Venue:</label>
                @foreach ($venues as $acronym => $venue)
                <div class="flex my-2">
                    <input type="radio"  name="venue" value=" {{ $acronym }}"
                     {{ old('venue') === $acronym ? 'checked': ''}}>
                    <label for="{{ $acronym }}" class="ml-2 form-label is-sm "> {{ $venue }} </label>
                </div>
                @endforeach
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="scopus_indexed"  id ="scopus_indexed" class="mr-2" value='1'
                {{old('scopus_indexed') === '1' ? 'checked': ''}}>
                <label for="scopus_indexed" class="w-full form-label">
                    Scopus Indexed ?
                </label>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
@endsection