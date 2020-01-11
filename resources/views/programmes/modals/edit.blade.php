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
                    <v-multi-typeahead
                        class="{{ $errors->has('courses') ? 'border-red-600' : ''}}"
                        name="courses[]"    
                        source="/api/courses"
                        find-source="/api/courses/{value}"
                        limit="5"
                        :value=data('programme_courses')
                        placeholder="Courses"
                    >
                </v-multi-typeahead>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
