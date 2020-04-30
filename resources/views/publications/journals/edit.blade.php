@extends('layouts.research')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Update Journal</h2>
        </div>
        <form action="{{ route('publications.journal.update' , $journal )}}" method="post" class="px-6">
            @csrf_token
            @method('PATCH')
            <div class="mb-4">
                <add-remove-elements :existing-elements ="{{ is_array(old('authors')) ? json_encode(old('authors')) : json_encode($journal->authors)}}">
                    <template v-slot="{ elements, addElement, removeElement }">
                        <div class="flex items-baseline mb-2">
                            <label for="authors[]" class="form-label block mb-1">
                                Authors <span class="text-red-600">*</span>
                            </label>
                            <button v-on:click.prevent="addElement" class="ml-auto btn is-sm text-blue-700 bg-gray-300">+</button>
                        </div>
                        <div v-for="(element, index) in elements" :key="index" class="flex items-baseline">
                            <input type="text" v-model= "element" name="authors[]" class="form-input block mb-2 w-full">
                            <button v-on:click.prevent="removeElement(index)" class="btn is-sm ml-2 text-red-600">x</button>
                        </div>
                    </template>
                </add-remove-elements>
            </div>
            <div class="mb-4">
                <label for="paper_title" class="form-label block mb-1">
                    Paper Title <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('paper_title', $journal->paper_title) }}" name="paper_title"
                    class="form-input w-full {{ $errors->has('paper_title') ? ' border-red-600' : ''}}">
            </div>
            <div class="mb-4">
                <label for="name" class="form-label block mb-1">
                    Journal <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('name', $journal->name) }}" name="name"
                    class="form-input w-full {{ $errors->has('name') ? ' border-red-600' : ''}}">
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="date[]" class="form-label block mb-1">
                        Issue Date <span class="text-red-600">*</span>
                    </label>
                    <div class="flex">
                        <select name="date[month]" id="date_month" class="form-select flex-1">
                            @foreach($months as $month)
                            <option value="{{ $month }}"
                                {{ $month === old('date.month', $journal->date->format('F')) ? 'selected' : ''}}>
                                {{ $month }}
                            </option>
                            @endforeach
                        </select>
                        <select name="date[year]" id="date_year" class="form-select flex-1 ml-4">
                            @foreach(range($currentYear-10, $currentYear) as $year)
                            <option value="{{$year}}"
                                {{ $year== old('date.year', $journal->date->format('Y')) ? 'selected' : ''}}>
                                {{$year}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="ml-4 w-1/2">
                    <label for="volume" class="form-label block mb-1">
                        Volume
                    </label>
                    <input type="number" value="{{ old('volume', $journal->volume) }}" name="volume"
                        class="form-input w-full {{ $errors->has('volume') ? ' border-red-600' : ''}}">
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="number" class="form-label block mb-1">
                        Number
                    </label>
                    <input type="number" value="{{ old('number', $journal->number) }}" name="number"
                        class="form-input w-full {{ $errors->has('number') ? ' border-red-600' : ''}}">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="publisher" class="form-label block mb-1">
                        Publisher <span class="text-red-600">*</span>
                    </label>
                    <input type="text" value="{{ old('publisher', $journal->publisher) }}" name="publisher"
                        class="form-input w-full {{ $errors->has('publisher') ? ' border-red-600' : ''}}">
                </div>
            </div>
            <div class="mb-4">
                <label for="page_numbers[]" class="form-label block mb-1">
                    Page Numbers <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <input type="number" value="{{ old('page_numbers.0', $journal->page_numbers[0]) }}" name="page_numbers[]"
                        class="form-input text-sm w-1/2 {{ $errors->has('page_numbers[0]') ? ' border-red-600' : ''}}">
                    <input type="number" value="{{ old('page_numbers.to', $journal->page_numbers[1]) }}" name="page_numbers[]"
                        class="form-input text-sm w-1/2 ml-4 {{ $errors->has('page_numbers[1]') ? ' border-red-600' : ''}}">
                </div>
            </div>
            <div class="mb-4">
                <label for="indexed_in[]" class="form-label block mb-1">
                    Indexed In <span class="text-red-600">*</span>
                </label>
                @foreach ($citationIndexes as $index)
                    @php($checked = in_array($index, array_map(
                        'strval', old('indexed_in', $journal->indexed_in)
                    )))
                    <div class="flex mb-1">
                        <input id="indexed-in-{{ $index }}"
                            type="checkbox"
                            class="form-checkbox"
                            name="indexed_in[]"
                            value="{{ $index }}"
                            {{ $checked ? 'checked': ''}} >
                        <label for="indexed-in-{{ $index }}"
                            class="ml-2 form-label is-sm">
                            {{ $index }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
    </div>
@endsection
