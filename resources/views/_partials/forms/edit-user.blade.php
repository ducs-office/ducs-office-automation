<form action="{{ route('staff.users.update', $user) }}" method="POST">
    @csrf_token
    @method('PATCH')
    <div class="flex mb-2 space-x-2">
        <div class="flex-1">
            <label for="edit_first_name" class="w-full form-label">First Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="edit_first_name" type="text" name="first_name" class="w-full form-input" required value="{{ old('first_name', $user->first_name) }}">
        </div>
        <div class="flex-1">
            <label for="edit_last_name" class="w-full form-label">Last Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="edit_last_name" type="text" name="last_name" class="w-full form-input" required value="{{ old('last_name', $user->last_name) }}">
        </div>
    </div>
    <div class="mb-2">
        <label for="edit_email" class="w-full form-label mb-1">
            Email <span class="h-current text-red-500 text-lg">*</span>
        </label>
        <input id="edit_email" type="email" name="email" class="w-full form-input" value="{{ old('email', $user->email) }}">
    </div>
    <div class="mb-2">
        <label for="edit_roles" class="w-full form-label mb-1">
            Roles <span class="h-current text-red-500 text-lg">*</span>
        </label>
        <select id="edit_roles" name="roles[]" class="w-full form-multiselect" multiple>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label for="edit_type" class="w-full form-label">Category<span
                class="h-current text-red-500 text-lg">*</span></label>
        <select name="type" id="edit_type" class="w-full form-select" required>
            <option value="" {{ old('category', $user->category) == '' ? 'selected' : '' }} disabled>
                -- Select a Category --
            </option>
            @foreach ($categories as $category)
            <option value="{{ $category }}"
                {{ old('category', $user->category) === $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
            @endforeach
        </select>
    </div>
    @if(! $user->isSupervisor())
    <div class="mb-2 flex items-center">
        <input type="checkbox" name="is_supervisor" id="edit_is_supervisor"
            class="form-checkbox mr-2" value="1">
        <label for="edit_is_supervisor" class="form-label">
            Make Supervisor ?
        </label>
    </div>
    @endif
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
