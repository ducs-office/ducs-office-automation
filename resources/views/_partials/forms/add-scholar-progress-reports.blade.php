<form action="{{ route('scholars.progress-reports.store', $scholar) }}" method="POST"
    class="space-y-3" enctype="multipart/form-data"
    x-data="{recommendation: '{{old('recommendation', '')}}' }">
    @csrf_token
    <div class="space-y-1">
        <label for="date" class="mb-1 w-full form-label"
            @error('date') text-red-500 @enderror">
            Date <span class="text-red-600">*</span>
        </label>
        <input type="date" name="date" id="date"
        class="w-full form-input @error('date') border-red-500 hover:border-red-700 @enderror">
        @error('date')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="recommendation" 
            class="mb-1 w-full form-label @error('recommendation') text-red-500 @enderror">
            Recommendation <span class="text-red-600">*</span>
        </label>
        <select name="recommendation" x-model="recommendation"
            class="w-full form-select @error('recommendation') border-red-500 hover:border-red-700 @enderror">
            <option class="text-gray-600" selected disabled value="">Select Recommendation</option>
            @foreach ($recommendations as $recommendation)
                <option value="{{ $recommendation }}" class="text-gray-600"> {{ $recommendation }} </option>
            @endforeach
        </select>
        @error('recommendation')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="progress_report" 
            class="w-full form-label mb-1 @error('progress_report') text-red-500 @enderror">
            Upload Progress Report <span class="text-red-600">*</span>
        </label>
        <x-input.file name="progress_report" id="progress_report" class="w-full form-input inline-flex items-center {{ $errors->has('progress-report') ? 'border-red-500 hover:border-red-700' : '' }}"
            accept="application/pdf,image/*"
            placeholder="Upload Document"
            required/>
        @error('progress_report')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
</form>