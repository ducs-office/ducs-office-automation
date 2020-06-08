
<div class="flex-1 space-y-1">
    <label for="teaching-detail-programme"
        class="w-full form-label @error('programme_revision_id') text-red-500 @enderror">
        Programme <span class="text-red-500">*</span>
    </label>
    <livewire:typeahead-programmes-latest-revision
        name="programme_revision_id"
        limit="10"
        :value="old('programme_revision_id')"
        placeholder="Select a Programme..."
        search-placeholder="Search Programme..."
    />
    @error('programme_revision_id')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
<div class="flex-1 space-y-1">
    <label for="teaching-detail-course"
        class="w-full form-label @error('course_id') text-red-500 @enderror">
        Course <span class="text-red-500">*</span>
    </label>
    <livewire:typeahead-courses
        name="course_id"
        limit="10"
        :value="old('course_id')"
        placeholder="Select a Course..."
        search-placeholder="Search Courses..."/>
    @error('course_id')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
<div>
    <button class="btn btn-magenta">Add</button>
</div>
