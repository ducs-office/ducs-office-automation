<form action="{{ route('staff.users.store') }}" method="POST">
    @csrf_token
    <div class="flex mb-2 space-x-2">
        <div>
            <label for="first_name" class="w-full form-label">First Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="first_name" type="text" name="first_name" class="w-full form-input" required>
        </div>
        <div>
            <label for="last_name" class="w-full form-label">Last Name<span class="h-current text-red-500 text-lg">*</span></label>
            <input id="last_name" type="text" name="last_name" class="w-full form-input" required>
        </div>
    </div>
    <div class="mb-2">
        <label for="email" class="w-full form-label">Email<span
                class="h-current text-red-500 text-lg">*</span></label>
        <input id="email" type="email" name="email" class="w-full form-input"
            placholder="Enter user's email here..." required>
    </div>
    <div class="mb-2">
        <label for="role" class="w-full form-label">Role<span
                class="h-current text-red-500 text-lg">*</span></label>
        <select id="role" name="roles[]" class="w-full form-multiselect" multiple>
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label for="category" class="w-full form-label">Category<span
                class="h-current text-red-500 text-lg">*</span></label>
        <select name="category" id="category" class="w-full form-select" required>
            <option value="" selected disabled>Select a Category:</option>
            @foreach($categories as $category)
            <option value="{{ $category }}">{{ $category }} </option>
            @endforeach
        </select>
    </div>
    <div class="mb-2 flex items-center">
        <input type="checkbox" name="is_supervisor" id="is_supervisor" class="form-checkbox mr-2" value="1">
        <label for="is_supervisor" class="w-full form-label">
            Is a Supervisor ?
        </label>
    </div>
    <div class="mt-5">
        <button class="btn btn-magenta">Create</button>
    </div>
</form>
