<form action="{{ route('scholars.documents.store', $scholar) }}" method="POST"
    class="px-6" enctype="multipart/form-data"
    x-data="{type: '{{ old('type') }}'}">
    @csrf_token
    <div class="mb-2 items-center">
        <div class="mb-2">
            <label for="date" class="mb-1 w-full form-label @error('date', 'addDocuments') text-red-500 @enderror">Date
                <span class="text-red-600">*</span>
            </label>
            <input type="date" name="date" id="date" class="w-full form-input @error('date', 'addDocuments') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('date') }}"
                required>
        @error('date', 'addDocuments')
            <p class="text-red-500"> {{ $message }} </p>
        @enderror
        </div>
        <div class="mb-2">
            <label for="document" class="mb-1 w-full form-label @error('document', 'addDocuments') text-red-500 @enderror">Upload Document
                <span class="text-red-600">*</span>
            </label>
            <x-input.file name="document" id="document"
            class="w-full form-input inline-flex items-center {{ $errors->has('document') ? 'border-red-500 hover:border-red-700' : '' }}"
            accept="application/pdf,image/*"
            placeholder="Upload Document"
            required/>
        @error('document', 'addDocuments')
            <p class="text-red-500"> {{ $message }} </p>
        @enderror
        </div>
        <div class="mb-2">
            <label for="type" class="mb-1 form-label w-full @error('type', 'addDocuments') text-red-500 @enderror">
                Type <span class="text-red-600">*</span>
            </label>
            <select name="type" id="type"
            class="form-select w-full @error('type', 'addDocuments') border-red-500 hover:border-red-700 @enderror"
            x-model="type">
                @foreach ($documentTypes as $documentType)
                    <option value="{{ $documentType }}">
                        {{$documentType}} </option>
                @endforeach
            </select>
        @error('type', 'addDocuments')
            <p class="text-red-500"> {{ $message }} </p>
        @enderror
        </div>
        <div class="mb-2">
            <label for="description" class="mb-1 w-full form-label @error('description', 'addDocuments') text-red-500 @enderror">Description
            </label>
            <textarea id="description" name="description" type="" class="w-full form-input @error('description', 'addDocuments') border-red-500 hover:border-red-700 @enderror" placeholder="Enter Description">{{ old('description') }}</textarea>
        @error('description', 'addDocuments')
            <p class="text-red-500"> {{ $message }} </p>
        @enderror
        </div>
    </div>
    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
</form>
