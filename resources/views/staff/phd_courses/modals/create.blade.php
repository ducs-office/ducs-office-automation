<v-modal name="create-courses-modal" height="auto">
    <form action="{{ route('staff.phd_courses.index') }}" method="POST" class="p-6" enctype="multipart/form-data">
        <h2 class="mb-8 font-bold text-lg">Create New PhD Course</h2>
        @csrf_token
        <div class="mb-2">
            <label for="unique-course-code" class="w-full form-label mb-1">Unique Course Code<span
                    class="text-red-600">*</span></label>
            <input id="unique-course-code" name="code" type="text" class="w-full form-input" placeholder="e.g. 4234201">
        </div>
        <div class="mb-2">
            <label for="course-name" class="w-full form-label mb-1">
                Course Name <span class="text-red-600">*</span>
            </label>
            <input id="course-name" type="text" name="name" class="w-full form-input"
                placeholder="e.g. Artificial Intelligence">
        </div>
        <div class="mb-2">
            <label for="course_type" class="w-full form-label">
                Type <span class="text-red-600">*</span>
            </label>
            <select class="w-full form-input" name="type" required>
                @foreach ($courseTypes as $type)
                <option value="{{ $type }}"
                    {{ old('type') === $type ? 'selected' : ''}}>
                    {{ $type }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</v-modal>
