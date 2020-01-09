<v-modal name="create-courses-modal" height="auto">
    <form action="{{ route('courses.index') }}" method="POST" class="p-6">
        <h2 class="mb-8 font-bold text-lg">Create New Course</h2>
        @csrf_token
        <div class="mb-2">
            <label for="unique-course-code" class="w-full form-label mb-1">Unique Course Code<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="unique-course-code" name="code" type="text" class="w-full form-input" placeholder="e.g. 4234201">
        </div>
        <div class="mb-2">
            <label for="course-name" class="w-full form-label mb-1">Course Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="course-name" type="text" name="name" class="w-full form-input"
                placeholder="e.g. Artificial Intelligence">
        </div>
        <div class="mb-2">
            <label for="course-programme" class="w-full form-label mb-1">Programme<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <select id="course-programme" name="programme_id" class="w-full form-input">
                <option value="" selected disabled>-- Select a Programme --</option>
                @foreach ($programmes as $id => $programme)
                <option value="{{ $id }}">{{ $programme }}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-6 mb-3">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</v-modal>
