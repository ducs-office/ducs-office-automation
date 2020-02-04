<v-modal name="create-courses-modal" height="auto">
    <form action="{{ route('staff.courses.index') }}" method="POST" class="p-6" enctype="multipart/form-data">
        <h2 class="mb-8 font-bold text-lg">Create New Course</h2>
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
                @foreach ($course_types as $key => $type)
                <option value="{{ $key }}"
                    {{ old('type', 'C') === $key ? 'selected' : ''}}>
                    {{ $type }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end mb-2">
            <div class="flex-1 mr-1">
                <label for="course_revision_date" class="w-full form-label mb-1">
                    Syllabus Revision Date <span class="text-red-600">*</span>
                </label>
                <input type="date" name="date" id="course_revision_date" class="w-full form-input">
            </div>
            <div class="flex-1 ml-1">
                <v-file-input id="course_attachments"
                    name="attachments[]"
                    accept="application/pdf, image/*"
                    placeholder="select multiple files"
                    multiple required>
                    <template v-slot="{ label }">
                        <span class="w-full form-label mb-1">
                            Upload Syllabus <span class="text-red-600">*</span>
                        </span>
                        <div class="w-full form-input inline-flex items-center" tabindex="0">
                            <feather-icon name="upload" class="h-4 mr-2 text-gray-700 flex-shrink-0"></feather-icon>
                            @{{ label }}
                        </div>
                    </template>
                </v-file-input>
            </div>
        </div>
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
    </form>
</v-modal>
