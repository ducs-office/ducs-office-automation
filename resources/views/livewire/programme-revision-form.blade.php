<div class="space-y-3">
    <div class="space-y-1">
        <label for="programme-date"
            class="w-full form-label @error('revised_at') text-red-500 @enderror">
            Revision Date (w.e.f) <span class="text-red-500">*</span>
        </label>
        <input id="programme-date" type="date" name="revised_at"
            class="w-full form-input @error('revised_at') border-red-500 hover:border-red-700 @enderror"
            value="{{ $revised_at }}"
            required>
        @error('revised_at')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <fieldset class="border rounded p-6 pt-4 @error('semester_courses') border-red-500 @enderror">
        <legend class="px-2 form-label text-base @error('semester_courses') text-red-500 @enderror">Semester-wise Courses</legend>
        @error('semester_courses')
            <p class="text-red-500 font-bold mb-1">{{ $message }}</p>
        @enderror
        <p class="text-gray-700">Select multiple courses to each semester. Atleast one course must be added to each semester</p>
        @foreach (range(1, $programme->duration * 2) as $index => $semester)
            <div class="mt-3 space-y-1">
                <label for="semester-{{$semester}}-courses"
                    class="w-full form-label @error('semester_courses.' . $semester) text-red-500 @enderror">
                    Courses for Semester {{ $semester }}
                </label>
                <x-select id="semester-{{$semester}}-courses"
                    :class="$errors->has('semester_courses.' . $semester) ? 'border-red-500 hover:border-red-500' : ''"
                    name="semester_courses[{{ $semester }}][]"
                    :multiple="true"
                    :choices="$courses"
                    :value="$semester_courses[$semester]"
                    wire:model="semester_courses.{{ $semester }}">
                    @foreach($courses as $index => $course)
                        <li class="px-4 py-2 cursor-pointer" x-bind:class="{
                            'bg-magenta-700 text-white': isHighlighted({{ $index }}),
                            'bg-gray-100': isSelected({{ $course->id }}),
                        }"
                        x-on:mouseover="highlight({{ $index }})"
                        x-on:click.prevent="onOptionSelected()">
                            <div class="flex space-x-2 items-center">
                                <span>{{ $course->code }}</span>
                                <span>{{ $course->name   }}</span>
                            </div>
                        </li>
                    @endforeach
                    <x-slot name="selectedChoice">
                        <div class="inline-flex space-x-2 items-center">
                            <span x-text="selectedChoice.code"></span>
                            <span x-text="selectedChoice.name"></span>
                        </div>
                    </x-slot>
                </x-select>
                @error('semester_courses.' . $semester)
                    <p class="text-red-500 font-bold">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </fieldset>
</div>
