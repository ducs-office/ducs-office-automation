<form action="{{ route('staff.phd_courses.update', $course) }}" method="POST" enctype="multipart/form-data"
    class="space-y-3"
    x-data="{type: '{{ old('type', $course->type) }}'}">
    @csrf_token @method('PATCH')
    <div class="space-y-1">
        <label for="course_code"
            class="w-full form-label mb-1 @error('code') text-red-500 @enderror">
            Unique Course Code<span class="h-current text-red-500 text-lg">*</span>
        </label>
        <input id="course_code" type="text" name="code"
            class="w-full form-input @error('code') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('code', $course->code) }}">
        @error('code')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="course_name"
            class="w-full form-label mb-1 @error('name') text-red-500 @enderror">
            Course Name<span class="h-current text-red-500 text-lg">*</span>
        </label>
        <input id="course_name" type="text" name="name"
            class="w-full form-input @error('name') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('name', $course->name) }}">
        @error('name')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="course_type"
            class="w-full form-label @error('type') text-red-500 @enderror">
            Type<span class="h-current text-red-500 text-lg">*</span>
        </label>
        <select name="type" required
            class="w-full form-select @error('type') border-red-500 hover:border-red-700 @enderror"
            x-model="type">
            @foreach ($courseTypes as $type)
            <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
        @error('type')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
