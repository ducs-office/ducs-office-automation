@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Create Publication</h2>
        </div>
        <form action="{{ route('scholars.profile.publication.store')}}" method="post" class="px-6">
            @csrf_token
            <div class="mb-4">
                <add-remove-elements :existing-elements ="{{ json_encode(old('authors')) }}">
                    <template v-slot="{ elements, addElement, removeElement }">
                        <div class="flex items-baseline mb-2">
                            <label for="authors[]" class="form-label block mb-1">
                                Author(s) <span class="text-red-600">*</span>
                            </label>
                            <button v-on:click.prevent="addElement" class="ml-auto btn is-sm text-blue-700 bg-gray-300">+</button>
                        </div>
                        <div v-for="(element, index) in elements" :key="index" class="flex items-baseline">
                            <input type="text" v-model= "element.value" 
                                name="authors[]" class="form-input block mb-2 w-full" placeholder="Author's name">
                            <button v-on:click.prevent="removeElement(index)" v-if="elements.length > 1" class="btn is-sm ml-2 text-red-600">x</button>
                        </div>
                    </template>
                </add-remove-elements>
            </div>
            <div class="mb-4">
                <label for="title" class="form-label block mb-1">
                    Title <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('title') }}" name="title" 
                    class="form-input w-full {{ $errors->has('title') ? ' border-red-600' : ''}}"
                    placeholder="Title of the publication">
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="date" class="form-label block mb-1">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input type="date" value="{{ old('date') }}" name="date" 
                        class="form-input w-full {{ $errors->has('date') ? ' border-red-600' : ''}}">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="volume" class="form-label block mb-1">
                        Volume 
                    </label>
                    <input type="number" value="{{ old('volume') }}" name="volume" 
                        class="form-input w-full {{ $errors->has('volume') ? ' border-red-600' : ''}}"
                        placeholder="Volume Number">
                </div>
            </div>
            <div class="mb-4">
                <label for="venue[]" class="form-label block mb-1">
                    Venue <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <input type="text" value="{{ old('venue.city') }}" name="venue[city]" 
                        class="form-input text-sm w-1/2 {{ $errors->has('venue[city]') ? ' border-red-600' : ''}}"
                        placeholder="City">
                    <input type="text" value="{{ old('venue.country') }}" name="venue[country]" 
                        class="form-input text-sm w-1/2 ml-4 {{ $errors->has('venue[country]') ? ' border-red-600' : ''}}"
                        placeholder="country">
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="number" class="form-label block mb-1">
                        Number 
                    </label>
                    <input type="number" value="{{ old('number') }}" name="number" 
                        class="form-input w-full{{ $errors->has('number') ? ' border-red-600' : ''}}"
                        placeholder="Number">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="publisher" class="form-label block mb-1">
                        Publisher <span class="text-red-600">*</span>
                    </label>
                    <input type="text" value="{{ old('publisher') }}" name="publisher" 
                        class="form-input w-full {{ $errors->has('publisher') ? ' border-red-600' : ''}}"
                        placeholder="Publisher">
                </div>
            </div>
            <div class="mb-4">
                <label for="page_numbers[]" class="form-label block mb-1">
                    Page Numbers <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <input type="number" value="{{ old('page_numbers.from') }}" name="page_numbers[from]" 
                        class="form-input text-sm w-1/2 {{ $errors->has('page_numbers[from]') ? ' border-red-600' : ''}}"
                        placeholder="Starting From">
                    <input type="number" value="{{ old('page_numbers.to') }}" name="page_numbers[to]" 
                        class="form-input text-sm w-1/2 ml-4 {{ $errors->has('page_numbers[to]') ? ' border-red-600' : ''}}"
                        placeholder="Ending To">
                </div>
            </div>
            <div class="mb-4">
                <label for="indexed_in[]" class="form-label block mb-2">
                    Indexed In <span class="text-red-600">*</span>
                </label>
                @foreach ($indexedIn as $acronym => $index)
                    <div class="flex mb-1">
                        <input type="checkbox" name="indexed_in[]" id="{{ $acronym }}" value="{{$acronym}}"
                         {{ (is_array(old('indexed_in')) && in_array($acronym, old('indexed_in'))) ? 'checked' : '' }}
                         > 
                        <label for="{{ $acronym }}" class="ml-2 form-label is-sm"> {{ $index }}</label>
                    </div>
                @endforeach
            </div>
            <div class="mb-4">
                <label for="conference" class="form-label block mb-1">
                    Conference <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('conference') }}" name="conference" 
                    class="form-input w-full {{ $errors->has('conference') ? ' border-red-600' : ''}}"
                    placeholder="Name of conference/ journal">
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
@endsection