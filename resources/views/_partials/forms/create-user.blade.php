<form action="{{ route('staff.users.store') }}" method="POST"
    class="space-y-3"
    x-data="userForm({{ json_encode(old()) }})">
    @csrf_token
    <div class="flex mb-2 space-x-2">
        <div class="space-y-1 flex-1">
            <label for="first_name"
                class="w-full form-label @error('first_name') text-red-500 @enderror">
                First Name <span class="text-red-500">*</span>
            </label>
            <input id="first_name" type="text" name="first_name"
                class="w-full form-input @error('first_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('first_name') }}"
                required>
            @error('first_name')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-1 flex-1">
            <label for="last_name"
                class="w-full form-label @error('last_name') text-red-500 @enderror">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input id="last_name" type="text" name="last_name"
                class="w-full form-input @error('last_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('last_name') }}"
                required>
            @error('last_name')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="space-y-1">
        <label for="email"
            class="w-full form-label @error('email') text-red-500 @enderror">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="email" type="email" name="email"
            placeholder="e.g user@example.com"
            class="w-full form-input @error('email') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('email') }}"
            required>
        @error('email')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="category"
            class="w-full form-label @error('category') text-red-500 @enderror">
            Category <span class="text-red-500">*</span>
        </label>
        <select id="category" name="category"
            class="w-full form-select @error('category') border-red-500 hover:border-red-700 @enderror"
            x-model="$form.category"
            required>
            <option value="" disabled>-- Select User category --</option>
            @foreach($categories as $category)
            <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
        @error('category')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div x-show="$form.isExternal()"
        class="mb-2 flex space-x-2">
        <div class="space-y-1 flex-1">
            <label for="designation"
                class="w-full form-label @error('designation') text-red-500 @enderror">
                Designation <span class="text-red-500">*</span>
            </label>
            <input id="designation" type="text" name="designation"
                class="w-full form-input @error('designation') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('designation') }}"
                x-bind:required="$form.isExternal()">
            @error('designation')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-1 flex-1">
            <label for="affiliation"
                class="w-full form-label @error('affiliation') text-red-500 @enderror">
                Affiliation <span class="text-red-500">*</span>
            </label>
            <input id="affiliation" type="text" name="affiliation"
                class="w-full form-input @error('affiliation') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('affiliation') }}"
                x-bind:required="$form.isExternal()">
            @error('affiliation')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div x-show="$form.isExternal()" class="mb-2">
        <label for="address" class="w-full form-label @error('address') text-red-500 @enderror">
            Address <span class="text-red-600">*</span>
        </label>
        <textarea id="address"
            class="w-full form-textarea @error('address') border-red-500 hover:border-red-700 @enderror"
            rows="2"
            name="address"
            placeholder="e.g. 142, M Block, East of Kailash, New Delhi, 110065"
            x-bind:required="$form.isExternal()">{{ old('address') }}</textarea>
        @error('address')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1" x-show="! $form.isExternal()">
        <fieldset class="mb-2 border rounded px-4 py-2 @error('roles') border-red-500 hover:border-red-700 @enderror">
            <legend for="edit_roles" class="form-label px-2 @error('roles') text-red-500 @enderror">
                Roles <span class="h-current text-red-500 text-lg" x-show="! $form.isCollegeTeacher()">*</span>
            </legend>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($roles as $role)
                <label for="role-{{ $role->id }}" class="form-label capitalize">
                    <input type="checkbox" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->id }}"
                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                        class="form-checkbox text-lg mr-1">
                    {{ $role->name }}
                </label>
                @endforeach
            </div>
        </fieldset>
        @error('address')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-2 flex items-center" x-show="$form.canBeSupervisor()">
        <input type="checkbox" name="is_supervisor" id="is_supervisor" class="form-checkbox mr-2" x-model="$form.isSupervisor">
        <label for="is_supervisor" class="w-full form-label">
            Is a Supervisor?
        </label>
    </div>
    <div class="mb-2 flex items-center" x-show="$form.canBeCosupervisor()">
        <input type="checkbox" name="is_cosupervisor" id="is_cosupervisor" class="form-checkbox mr-2" x-model="$form.isCosupervisor">
        <label for="is_cosupervisor" class="w-full form-label">
            Is a Co-supervisor ?
        </label>
    </div>
    <div class="mt-5">
        <button class="btn btn-magenta">Create</button>
    </div>
</form>
@push('scripts')
<script>
    function userForm(old)
    {
        return {
            $form: {
                category: old.category || '',
                isSupervisor: old.is_supervisor ? true : false,
                isCosupervisor: old.is_cosupervisor ? true : false,
                isExternal() {
                    return this.category === '{{ App\Types\UserCategory::EXTERNAL }}';
                },
                isCollegeTeacher() {
                    return this.category === '{{ App\Types\UserCategory::COLLEGE_TEACHER }}';
                },
                canBeSupervisor() {
                    return [
                        '{{ App\Types\UserCategory::COLLEGE_TEACHER }}',
                        '{{ App\Types\UserCategory::FACULTY_TEACHER }}',
                    ].includes(this.category);
                },
                canBeCosupervisor() {
                    return !this.isSupervisor && (this.canBeSupervisor() || this.isExternal());
                }
            }
        }
    }
</script>
@endpush
