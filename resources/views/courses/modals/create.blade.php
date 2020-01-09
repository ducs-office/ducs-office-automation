<v-modal name="create-courses-modal" height="auto">
    <form action="{{ route('courses.index') }}" method="POST" class="p-6" enctype="multipart/form-data">
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
            <label for="file" class="w-full form-label mb-1">Upload Syllabus</label>
            <input type="file" name="attachments[]" accept="application/pdf, image/*" class="w-full" multiple>
        </div>
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</v-modal>
