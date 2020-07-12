<form action="{{ $route }}" method="post"
    class="px-6" enctype="multipart/form-data"
    x-data="{ publication_type: '{{old('type',App\Types\PublicationType::JOURNAL)}}', is_published: '{{array_key_exists('is_published', old())}}'}">
    @csrf_token
    <div class="mb-4">
        <label for="type" class="form-label block mb-1">
            Type of Publication <span class="text-red-600">*</span>
        </label>
        <select name="type" id="type" class="form-select w-full"
            x-model="publication_type">
            @foreach ($types as $type)
                <option value="{{ $type }}">
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="paper_title" class="form-label block mb-1">
            Paper Title <span class="text-red-600">*</span>
        </label>
        <input type="text" value="{{ old('paper_title') }}" name="paper_title"
            class="form-input w-full {{ $errors->has('paper_title') ? ' border-red-600' : ''}}"
            placeholder="Title of the publication">
    </div>
    <div class="flex mb-4">
        <input type="checkbox" name="is_published" id="is_published"
            class="form-checkbox" x-model="is_published">
        <label for="number" class="form-label block ml-2">
            Is the paper published?
        </label>
    </div>
    @auth('scholars')    
    <div class="mb-4">
        <label for="document" class="form-label block mb-1"
        x-show="is_published">
            Upload the first page of the publication<span class="text-red-600">*</span>
        </label>
        <label for="document" class="form-label block mb-1"
        x-show="!is_published">
            Upload the acceptance letter for the publication<span class="text-red-600">*</span>
        </label>
        <x-input.file name="document" id="document"
            class="w-full form-input inline-flex items-center"
            accept="application/pdf,image/*"
            placeholder="Upload Document"
            required/>
    </div>
    @endauth
    <livewire:add-remove
        view="livewire.co-authors-list"
        items-name="coAuthors"
        :new-item="['name' => '', 'noc' => '']"
     />
    <div x-show="is_published">
        <div class="mb-4">
            <label for="name" class="form-label block mb-1"
            x-show="publication_type == '{{App\Types\PublicationType::JOURNAL}}'">
                Journal<span class="text-red-600">*</span>
            </label>
            <label for="name" class="form-label block mb-1"
            x-show="publication_type == '{{App\Types\PublicationType::CONFERENCE}}'">
                Conference<span class="text-red-600">*</span>
            </label>
            <input type="text" value="{{ old('name') }}" name="name"
            class="form-input w-full {{ $errors->has('name') ? ' border-red-600' : ''}}"
            placeholder="Enter the name here">
        </div>
        <div class="mb-4">
            <label for="paper_link" class="form-label block mb-1">
                Link for the Paper
            </label>
            <input type="text" name="paper_link" id="paper_link" value="{{ old('paper_link') }}"
            class="form-input w-full {{ $errors->has('paper_link') ? ' border-red-600' : ''}}""
            placeholder="Link for the paper published">
        </div>
        <div class="flex mb-4">
            <div class="w-1/2">
                <label for="date[]" class="form-label block mb-1">
                    Date <span class="text-red-600">*</span>
                </label>
                <div class="flex">
                    <select name="date[month]" id="date_month" class="form-select flex-1">
                        @foreach($months as $month)
                        <option value="{{ $month }}"
                        :selected="'{{ $month === old('date.month', now()->format('F')) }}'">
                            {{ $month }}
                        </option>;
                        @endforeach
                    </select>
                    <select name="date[year]" id="date_year" class="form-select flex-1 ml-4">
                        @foreach(range($currentYear-10, $currentYear) as $year)
                        <option value="{{ $year}}"
                        :selected="'{{ $year== old('date.year', now()->format('Y'))}}'">
                            {{$year}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ml-4 w-1/2">
                <label for="volume" class="form-label block mb-1">
                    Volume/ Edition
                </label>
                <input type="number" value="{{ old('volume') }}" name="volume"
                    class="form-input w-full {{ $errors->has('volume') ? ' border-red-600' : ''}}"
                    placeholder="Volume/Edition Number">
            </div>
        </div>
        <div class="flex mb-4" x-show="publication_type == '{{App\Types\PublicationType::JOURNAL}}'">
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
        <div class="mb-4 flex" x-show="publication_type == '{{App\Types\PublicationType::CONFERENCE}}'">
            <div class="w-1/2">
                <label for="city" class="form-label block mb-1">
                    City <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('city') }}" name="city"
                    class="form-input text-sm w-full {{ $errors->has('city') ? ' border-red-600' : ''}}"
                    placeholder="City">
            </div>
            <div class="ml-4 w-1/2">
                <label for="country" class="form-label block mb-1">
                    Country <span class="text-red-600">*</span>
                </label>
                <input type="text" value="{{ old('country') }}" name="country"
                    class="form-input text-sm w-full {{ $errors->has('country') ? ' border-red-600' : ''}}"
                    placeholder="Country">
            </div>
        </div>
        <div class="mb-4">
            <label for="page_numbers[]" class="form-label block mb-1">
                Page Numbers <span class="text-red-600">*</span>
            </label>
            <div class="flex">
                <input type="number" value="{{ old('page_numbers.0') }}" name="page_numbers[]"
                    class="form-input text-sm w-1/2 {{ $errors->has('page_numbers[0]') ? ' border-red-600' : ''}}"
                    placeholder="Starting From">
                <input type="number" value="{{ old('page_numbers.1') }}" name="page_numbers[]"
                    class="form-input text-sm w-1/2 ml-4 {{ $errors->has('page_numbers[1]') ? ' border-red-600' : ''}}"
                    placeholder="Ending To">
            </div>
        </div>
        <div class="mb-4">
            <label for="indexed_in[]" class="form-label block mb-2">
                Indexed In <span class="text-red-600">*</span>
            </label>
            @foreach ($citationIndexes as $index)
                <div class="flex mb-1">
                    <input type="checkbox" name="indexed_in[]" id="indexed-in-{{ $index }}" value="{{$index}}"
                        class="form-checkbox"
                        {{ in_array($index, old('indexed_in', [])) ? 'checked' : '' }}>
                    <label for="indexed-in-{{ $index }}" class="ml-2 form-label is-sm"> {{ $index }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-6">
        <button type="submit" class="w-full btn btn-magenta">Create</button>
    </div>
</form>