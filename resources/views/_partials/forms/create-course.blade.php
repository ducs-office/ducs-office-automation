<form action="{{ route('staff.courses.store') }}" method="POST" 
    class="space-y-3" 
    enctype="multipart/form-data"
    x-data="{ type: '{{ old('type') }}' }">
    @csrf_token
    <div class="space-y-1">
        <label for="unique-course-code" 
            class="w-full form-label @error('code') text-red-500 @enderror">
            Unique Course Code<span class="text-red-600">*</span>
        </label>
        <input id="unique-course-code" name="code" type="text" 
            class="w-full form-input @error('code') border-red-500 hover:border-red-700 @enderror" 
            placeholder="e.g. 4234201"
            value="{{ old('code') }}"
            required>
        @error('code')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="course-name" 
            class="w-full form-label @error('name') text-red-500 @enderror">
            Course Name <span class="text-red-600">*</span>
        </label>
        <input id="course-name" type="text" name="name" 
            class="w-full form-input @error('name') border-red-500 hover:border-red-700 @enderror"
            placeholder="e.g. Artificial Intelligence"
            value="{{ old('name') }}"
            required>
        @error('name')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="course_type" 
            class="w-full form-label @error('type') text-red-500 @enderror">
            Type <span class="text-red-600">*</span>
        </label>
        <select name="type"
            class="w-full form-select @error('type') border-red-500 hover:border-red-700 @enderror"
            x-model="type" 
            required>
            <option value="" disabled>-- Select Course Type --</option>
            @foreach ($courseTypes as $type)
            <option value="{{ $type }}"> {{ $type }} </option>
            @endforeach
        </select>
        @error('type')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex items-center space-x-2 mb-2">
        <div class="w-1/2 space-y-1">
            <label for="course_revision_date" 
                class="w-full form-label @error('date') text-red-500 @enderror">
                Syllabus Revision Date <span class="text-red-600">*</span>
            </label>
            <input type="date" name="date" id="course_revision_date" 
            class="w-full form-input @error('date') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('date') }}"
            required>
            @error('date')
                <p class="red-text-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="w-1/2 space-y-1"> 
            <label for="course_attachments" 
                class="w-full form-label @error('attachments') text-red-500 @enderror">
                Upload Syllabus <span class="text-red-600">*</span>
            </label>
            <x-input.file id="course_attachments" name="attachments[]"
                class="w-full form-input  inline-flex items-center {{ $errors->has('attachments') ? 'border-red-500 hover:border-red-700' : '' }}"
                tabindex="0"
                accept="application/pdf, image/*"
                placeholder="select multiple files"
                multiple 
                required/>
            @error('attachments')
                <p class="red-text-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Create</button>
    </div>
</form>
