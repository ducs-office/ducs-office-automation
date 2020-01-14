<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Course</h2>
            <form :action="route('courses.update', data('course', ''))" method="POST" enctype="multipart/form-data">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="course_code" class="w-full form-label mb-1">Unique Course Code<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="course_code" type="text" name="code" class="w-full form-input" :value="data('course.code')">
                </div>
                <div class="mb-2">
                    <label for="course_name" class="w-full form-label mb-1">Course Name<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="course_name" type="text" name="name" class="w-full form-input" :value="data('course.name')">
                </div>
                <div class="mb-2">
                    <label for="course_type" class="w-full form-label">Type<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select class="w-full form-input" name="type" required :value="data('course.type')">
                        <option value="CORE"
                            {{ old('type', 'CORE') === 'CORE' ? 'selected' : ''}}>Core
                        </option>
                        <option value="GE"
                            {{ old('type', 'GE') === 'GE' ? 'selected' : ''}}>General Elective
                        </option>
                        <option value="OE"
                            {{ old('type', 'OE') === 'OE' ? 'selected' : ''}}>Open Elective
                        </option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="file" class="w-full form-label mb-1">Upload Syllabus</label>
                    <input type="file" name="attachments[]" accept="application/pdf, image/*" class="w-full" multiple>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
