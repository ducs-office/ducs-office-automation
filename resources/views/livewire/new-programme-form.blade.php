<form action="{{ route('staff.programmes.store') }}"
    method="POST">
    @csrf_token
    <div class="flex flex-col space-y-4">
        <div class="flex-1 space-y-4">
            <div class="flex items-start space-x-2">
                <div class="w-48 space-y-1">
                    <label for="programme-code"
                        class="w-full form-label @error('code') text-red-500 @enderror">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input id="programme-code" type="text" name="code"
                        class="w-full form-input @error('code') border-red-500 hover:border-red-700 @enderror"
                        wire:model.lazy="code"
                        autofocus
                        required>
                    @error('code')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-1 space-y-1">
                    <label for="programme-date"
                        class="w-full form-label @error('wef') text-red-500 @enderror">
                        Date (w.e.f) <span class="text-red-500">*</span>
                    </label>
                    <input id="programme-date" type="date" name="wef"
                        class="w-full form-input @error('wef') border-red-500 hover:border-red-700 @enderror"
                        value="{{ old('wef') }}"
                        required>
                    @error('wef')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="space-y-1">
                <label for="programme-name"
                    class="w-full form-label @error('name') text-red-500 @enderror">
                    Name <span class="text-red-500">*</span>
                </label>
                <input id="programme-name" type="text" name="name"
                    class="w-full form-input @error('name') border-red-500 hover:border-red-700 @enderror"
                    value="{{ old('name') }}"
                    required>
                @error('name')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-start space-x-2">
                <div class="flex-1 space-y-1">
                    <label for="programme-type"
                        class="w-full form-label @error('type') text-red-500 @enderror">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select id="programme-type" name="type"
                        class="w-full form-select @error('type') border-red-500 hover:border-red-700 @enderror"
                        wire:model="type"
                        required>
                        <option value="" selected disabled>-- Select Programme Type --</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-48 space-y-1">
                    <label for="programme-duration"
                        class="w-full form-label @error('duration') text-red-500 @enderror">
                        Duration (in years) <span class="text-red-500">*</span>
                    </label>
                    <input id="programme-duration" type="number" name="duration"
                        class="w-full form-input @error('duration') border-red-500 hover:border-red-700 @enderror"
                        wire:model="duration"
                        required>
                    @error('duration')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <fieldset class="border rounded p-6 flex-1">
            <legend class="px-2 form-label text-base">Semester-wise Courses</legend>
            <p class="text-gray-700">Select multiple courses to each semester. Atleast one course must be added to each semester</p>
            @foreach (range(1, $duration * 2) as $index => $semester)
                <div class="mt-3 space-y-1">
                    <label for="semester-{{$semester}}-courses"
                    class="w-full form-label"
                    >Courses for Semester {{ $semester }}</label>
                    <x-select id="semester-{{$semester}}-courses"
                        :class="$errors->has('semester_courses.' . $semester) ? 'border-red-500 hover:border-red-500' : ''"
                        name="semester_courses[{{ $semester }}][]"
                        :multiple="true"
                        :choices="$courses"
                        :value="$semester_courses[$semester]"
                        {{-- this is causing re-render which closes the select input as one item is selected. --}}
                        wire:model="semester_courses.{{ $semester }}"
                        >
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
                </div>
            @endforeach
        </fieldset>
    </div>
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Create</button>
    </div>
</form>
