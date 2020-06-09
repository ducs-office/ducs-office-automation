<form action="{{ route('profiles.update', $user) }}" method="POST" class="space-y-3"
x-data="{status: '{{ old('status', $user->status) }}', designation: '{{ old('designation', $user->designation) }}'}">
@csrf_token
@method('PATCH')
@if ($user->isCollegeTeacher() || $user->isFacultyTeacher())
<div class="space-y-1 mb-2">
    <label for="edit-status"
        class="w-full form-label mb-1 @error('status', 'update') text-red-500 @enderror">
        Status
    </label>
    <select name="status" id="edit-status"
        class="w-full form-select @error('status', 'update') border-red-500 hover:border-red-700 @enderror"
        x-model="status">
        @foreach ($teacherStatus as $status)
        <option value="{{ $status }}">{{ $status }}</option>
        @endforeach
    </select>
    @error('status', 'update')
       <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
<div class="space-y-1 mb-2">
    <label for="edit-designation" class="w-full form-label mb-1 @error('designation', 'update') text-red-500 @enderror">
        Designation
    </label>
    <select name="designation" id="edit-designation"
        class="w-full form-select @error('designation', 'update') border-red-500 hover:border-red-700 @enderror"
        x-model="designation">
        @foreach ($designations as $designation)
        <option value="{{ $designation }}">{{ $designation }}</option>
        @endforeach
    </select>
</div>
@error('designation', 'update')
    <p class="text-red-500">{{ $message }}</p>
@enderror
@else
<div class="space-y-1 mb-2">
    <label for="edit-designation" class="w-full form-label mb-1 @error('designation', 'update') text-red-500 @enderror">
        Designation
    </label>
    <input id="edit-designation" type="text" name="designation"
        class="w-full form-input @error('designation', 'update') border-red-500 hover:border-red-700 @enderror"
        value="{{ old('designation', $user->designation) }}">
    @error('designation', 'update')
    <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
@endif
@if ($user->isExternal())
<div class="space-y-1 mb-2">
    <label for="edit-affiliation" class="w-full form-label mb-1 @error('affiliation', 'update') text-red-500 @enderror">
        Affiliation
    </label>
    <input id="edit-affiliation" type="text" name="affiliation"
        class="w-full form-input @error('affiliation', 'update') border-red-500 hover:border-red-700 @enderror"
        value="{{ old('affiliation', $user->affiliation) }}">
    @error('affiliation', 'update')
    <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
@else
<div class="space-y-1 mb-2">
    <label for="college_id" class="w-full form-label mb-1 @error('college_id', 'update') text-red-500 @enderror">
        College/Department
    </label>
    <livewire:typeahead-colleges
        name="college_id"
        limit="5"
        value="{{ old('college_id', $user->college_id) }}"
        placeholder="Enter College/Department Name"
        search-placeholder="search from DU Colleges..." />
    @error('college_id', 'update')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</div>
@endif
<div class="mt-5">
    <button type="submit" class="btn btn-magenta">Update</button>
</div>
</form>
