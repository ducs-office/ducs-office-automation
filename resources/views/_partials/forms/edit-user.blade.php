<form action="{{ route('staff.users.update', $user) }}" method="POST"
    class="space-y-3"
    x-data="userForm({
        category: '{{ old('category', $user->category) }}',
        is_supervisor: {{ json_encode(old('is_supervisor', false) || $user->isSupervisor()) }},
        is_cosupervisor: {{ json_encode(old('is_cosupervisor', false) || $user->isCosupervisor()) }},
    })">
    @csrf_token
    @method('PATCH')
    <div class="flex mb-2 space-x-2">
        <div class="space-y-1 flex-1">
            <label for="edit-first_name"
                class="w-full form-label @error('first_name') text-red-500 @enderror">
                First Name <span class="text-red-500">*</span>
            </label>
            <input id="edit-first_name" type="text" name="first_name"
                class="w-full form-input @error('first_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('first_name', $user->first_name) }}"
                required>
            @error('first_name')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-1 flex-1">
            <label for="edit-last_name"
                class="w-full form-label @error('last_name') text-red-500 @enderror">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input id="edit-last_name" type="text" name="last_name"
                class="w-full form-input @error('last_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('last_name', $user->last_name) }}"
                required>
            @error('last_name')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="space-y-1">
        <label for="edit-email"
            class="w-full form-label @error('email') text-red-500 @enderror">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="edit-email" type="text" name="email"
            class="w-full form-input @error('email') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('email', $user->email) }}"
            required>
        @error('email')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="edit-category"
            class="w-full form-label @error('category') text-red-500 @enderror">
            Category <span class="text-red-500">*</span>
        </label>
        <select id="edit-category" name="category"
            class="w-full form-select @error('category') border-red-500 hover:border-red-700 @enderror"
            x-model="$form.category"
            required>
            <option value="" disabled>-- Select User category --</option>
            @foreach($categories as $category)
            <option value="{{ $category }}" {{ old('category') == $category }}>{{ $category }}</option>
            @endforeach
        </select>
        @error('category')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div x-show="$form.isExternal()" class="mb-2 flex space-x-2">
        <div class="space-y-1 flex-1">
            <label for="edit-designation" class="w-full form-label @error('designation') text-red-500 @enderror">
                Designation <span class="text-red-500">*</span>
            </label>
            <input id="edit-designation" type="text" name="designation"
                class="w-full form-input @error('designation') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('designation', $user->designation) }}" x-bind:required="$form.isExternal()">
            @error('designation')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-1 flex-1">
            <label for="edit-affiliation" class="w-full form-label @error('affiliation') text-red-500 @enderror">
                Affiliation <span class="text-red-500">*</span>
            </label>
            <input id="edit-affiliation" type="text" name="affiliation"
                class="w-full form-input @error('affiliation') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('affiliation', $user->affiliation) }}" x-bind:required="$form.isExternal()">
            @error('affiliation')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div x-show="$form.isExternal()" class="mb-2">
        <label for="edit-address" class="w-full form-label @error('address') text-red-500 @enderror">
            Address <span class="text-red-600">*</span>
        </label>
        <textarea id="edit-address" class="w-full form-textarea @error('address') border-red-500 hover:border-red-700 @enderror"
            rows="2" name="address" placeholder="e.g. 142, M Block, East of Kailash, New Delhi, 110065"
            x-bind:required="$form.isExternal()">{{ old('address', $user->address) }}</textarea>
        @error('address')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1" x-show="! $form.isExternal()">
        <fieldset class="mb-2 border rounded px-4 py-2 @error('roles') border-red-500 hover:border-red-700 @enderror">
            <legend for="edit-roles" class="form-label px-2 @error('roles') text-red-500 @enderror">
                Roles <span class="h-current text-red-500 text-lg" x-show="! $form.isCollegeTeacher()">*</span>
            </legend>
            <div class="grid grid-cols-3 gap-2">
                @foreach ($roles as $role)
                <label for="edit-role-{{ $role->id }}" class="form-label capitalize">
                    <input type="checkbox" id="edit-role-{{ $role->id }}"
                        name="roles[]" value="{{ $role->id }}"
                        {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                        class="form-checkbox text-lg mr-1">
                    {{ $role->name }}
                </label>
                @endforeach
            </div>
        </fieldset>
        @error('roles')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    @if(! $user->isSupervisor())
    <div x-show="$form.canBeSupervisor()">
        <p class="text-gray-600 mb-1">
            <b>Note:</b>
            You cannot later change a supervisor to become co-supervisor,
            or stop a user from being a supervisor or co-supervisor.
            A co-supervisor however can be changed to become a supervisor.
        </p>
        <div class="flex items-center mb-2">
            <input type="checkbox" name="is_supervisor" id="edit-is-supervisor" class="form-checkbox mr-2"
                x-model="$form.isSupervisor">
            <label for="edit-is-supervisor" class="w-full form-label @error('is_supervisor') text-red-500 @enderror">
                Make Supervisor?
            </label>
            @error('is_supervisor')
            <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    @if(! $user->isCosupervisor())
    <div class="mb-2 flex items-center" x-show="$form.canBeCosupervisor()">
        <input type="checkbox" name="is_cosupervisor" id="edit-is-cosupervisor" class="form-checkbox mr-2"
            x-model="$form.isCosupervisor">
        <label for="edit-is-cosupervisor" class="w-full form-label @error('is_cosupervisor') text-red-500 @enderror">
            Make Co-supervisor ?
        </label>
        @error('is_cosupervisor')
        <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    @endif
    @endif
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
