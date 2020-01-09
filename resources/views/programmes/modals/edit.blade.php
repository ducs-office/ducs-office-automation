<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Programme</h2>
            <form :action="route('programmes.update', data('programme', ''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="programme_code" class="w-full form-label">
                        Code <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" :value="data('programme.code')">
                </div>
                <div class="mb-2">
                    <label class="w-full form-label">
                        Date (w.e.f) <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="date" name="wef" class="w-full form-input" :value="data('programme.wef')">
                </div>
                <div class="mb-2">
                    <label for="programme_name" class="w-full form-label">Name<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input" :value="data('programme.name')">
                </div>
                <div class="mb-2">
                    <label for="programme_duration" class="w-full form-label">Duration<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_duration" type="number" name="duration" class="w-full form-input" :value="data('programme.duration')">
                </div>
                <div class="mb-2">
                    <label for="programme_type" class="w-full form-label">Type<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select class="w-full form-input" name="type" required :value="data('programme.type')">
                        <option value="Under Graduate(U.G.)">Under Graduate(U.G.)</option>
                        <option value="Post Graduate(P.G.)">Post Graduate(P.G.)</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="programme_course" class="w-full form-label">Courses</label>
                    <div class="overflow-y-auto overflow-x-hidden h-32 border">
                        @foreach ($courses as $course)
                        <div class="flex justify-between mt-1 px-3 py-1">
                            <label for="course-{{ $course->id }}">{{ $course->name }} ({{ $course->code }}) </label>
                            <input id="course-{{ $course->id }}" type="checkbox" name="courses[]" :value="{{ $course->id }}"/>
                        </div>
                        @endforeach

                        <div class="flex justify-between mt-1 px-3 py-1"
                            v-for="course in data('programme.courses', [])"
                            :key="course.id">
                            <label :for="`course-${ course.id }`">
                                @verbatim
                                {{ course.name }} ({{ course.code }})
                                @endverbatim
                            </label>
                            <input :id="`course-${ course.id }`" type="checkbox" name="courses[]" :value=course.id checked/>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
