<v-modal name="course-update-modal" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Course</h2>
            <form :action="route('courses.update', data('course', ''))" method="POST">
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
                <div class="mb-5">
                    <label for="course_programme" class="w-full form-label mb-1">Programme<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select id="course_programme" name="programme_id" class="w-full form-input"
                        :value="data('course.programme_id')">
                        @foreach($programmes as $id => $programme)
                            <option value="{{ $id }}">{{ $programme }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
