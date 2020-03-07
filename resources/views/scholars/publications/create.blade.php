@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Create Publication</h2>
        </div>
        <form action="{{ route('scholars.profile.publication.store')}}" method="post" class="px-6">
            @csrf_token
            <div class="mb-4">
                <label for="authors[]" class="form-label block mb-1">
                    Authors <span class="text-red-600">*</span>
                </label>
                <div class="flex flex-wrap mt-2 justify-center">
                    <input type="text" value="{{ old('author') }}" name="authors[]" class="form-input block mb-2 ">
                </div>
            </div>
            <div class="mb-4">
                <label for="title" class="form-label block mb-1">
                    Title <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('title') }}" name="title" 
                    class="form-input w-full {{ $errors->has('title') ? ' border-red-600' : ''}}">
            </div>
            <div class="flex flex-wrap mb-4">
                <div class="w-1/2">
                    <label for="date" class="form-label block mb-1">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input type="date" value="{{ old('date') }}" name="date" 
                        class="form-input {{ $errors->has('date') ? ' border-red-600' : ''}}">
                </div>
                <div class="ml-auto w-1/2">
                    <label for="volume" class="form-label block mb-1">
                        Volume 
                    </label>
                    <input type="number" value="{{ old('volume') }}" name="volume" 
                        class="form-input {{ $errors->has('volume') ? ' border-red-600' : ''}}">
                </div>
            </div>
            <div class="mb-4">
                <label for="venue[]" class="form-label block mb-1">
                    Venue <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <div class="flex items-baseline mt-1 ml-4">
                        <label for="venue[city]" class="text-gray-700 text-sm mr-2">City</label>
                        <input type="text" value="{{ old('venue[city]') }}" name="venue[city]" 
                            class="form-input text-sm {{ $errors->has('venue[city]') ? ' border-red-600' : ''}}">
                    </div>
                    <div class="flex items-baseline mt-1 ml-4">
                        <label for="venue[Country]" class="text-gray-700 text-sm mr-2">Country</label>
                        <input type="text" value="{{ old('venue[Country]') }}" name="venue[Country]" 
                            class="form-input text-sm {{ $errors->has('venue[Country]') ? ' border-red-600' : ''}}">
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mb-4">
                <div class="w-1/2">
                    <label for="number" class="form-label block mb-1">
                        Number 
                    </label>
                    <input type="number" value="{{ old('number') }}" name="number" 
                        class="form-input {{ $errors->has('number') ? ' border-red-600' : ''}}">
                </div>
                <div class="ml-auto w-1/2">
                    <label for="publisher" class="form-label block mb-1">
                        Publisher <span class="text-red-600">*</span>
                    </label>
                    <input type="text" value="{{ old('publisher') }}" name="publisher" 
                        class="form-input {{ $errors->has('publisher') ? ' border-red-600' : ''}}">
                </div>
            </div>
            <div class="mb-4">
                <label for="page_numbers[]" class="form-label block mb-1">
                    Page Numbers <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <div class="flex items-baseline mt-1 ml-4">
                        <label for="page_numbers[from]" class="text-gray-700 text-sm mr-2">From</label>
                        <input type="text" value="{{ old('page_numbers[from]') }}" name="page_numbers[from]" 
                            class="form-input text-sm {{ $errors->has('page_numbers[from]') ? ' border-red-600' : ''}}">
                    </div>
                    <div class="flex items-baseline mt-1 ml-4">
                        <label for="page_numbers[to]" class="text-gray-700 text-sm mr-2">To</label>
                        <input type="text" value="{{ old('page_numbers[to]') }}" name="page_numbers[to]" 
                            class="form-input text-sm {{ $errors->has('page_numbers[to]') ? ' border-red-600' : ''}}">
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="indexed_in[]" class="form-label block mb-1">
                    Indexed In <span class="text-red-600">*</span>
                </label>
                <div class="flex flex-wrap mt-2 justify-center">
                    <input type="text" value="{{ old('indexed') }}" name="indexed_in[]" class="form-input block mb-2 ">
                </div>
            </div>
            <div class="mb-4">
                <label for="conference" class="form-label block mb-1">
                    Conference <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('conference') }}" name="conference" 
                    class="form-input w-full {{ $errors->has('conference') ? ' border-red-600' : ''}}">
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
@endsection