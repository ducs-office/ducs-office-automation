<form action="{{ route('staff.phd_courses.store') }}" method="POST" class="space-y-3" 
    enctype="multipart/form-data"
    x-data="phdCourseForm({{ json_encode(old()) }})">
    @csrf_token
    <div class="space-y-1">
        <label for="unique-course-code" 
            class="w-full form-label mb-1 @error('code') text-red-500 @enderror">
            Unique Course Code<span class="text-red-600">*</span>
        </label>
        <input id="unique-course-code" name="code" type="text" placeholder="e.g. 4234201" 
        class="w-full form-input @error('code') border-red-500 hover:border-red-700 @enderror">
        @error('code')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="course-name" 
            class="w-full form-label mb-1 @error('name') text-red-500 @enderror">
            Course Name <span class="text-red-600">*</span>
        </label>
        <input id="course-name" type="text" name="name" 
            class="w-full form-input @error('name') border-red-500 hover:border-red-700 @enderror"
            placeholder="e.g. Artificial Intelligence">
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
            class="w-full form-select @error('type') border-red-500 hover:border-red-700 @enderror " 
            x-model="$form.type"
            required>
            <option value="" disabled>-- Select Course Type --</option>
            @foreach ($courseTypes as $type)
            <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
        @error('type') 
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Create</button>
    </div>
</form>
@push('scripts')
<script>
    function phdCourseForm(old)
    {
        return {
            $form: {
                type: old.type || '',
            }
        }
    }
</script>
@endpush
