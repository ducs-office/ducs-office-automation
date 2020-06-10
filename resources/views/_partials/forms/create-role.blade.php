<form action="{{ route('staff.roles.store') }}" method="POST">
    @csrf_token
    <div class="mb-2">
        <label for="name" class="w-full form-label">Role Name<span
                class="h-current text-red-500 text-lg">*</span></label>
        <input id="name" type="text" name="name" class="w-full form-input"
            placeholder="Enter a name for the role..." required>
    </div>
    <div class="mb-2">
        <label for="permissions" class="w-full form-label">Choose Permissions <span
                class="h-current text-red-500 text-lg">*</span></label>
        <table class="text-left">
            @foreach ($permissions as $group => $gPermissions)
            <tr class="py-1">
                <th class="pr-4">{{ $group }}:</th>
                <td class="pl-4">
                    <x-grouped-permission-input name="permissions[]"
                        :old="old('permissions', [])"
                        :permissions="$gPermissions">
                    </x-grouped-permission-input>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="mt-5">
        <button class="btn btn-magenta">Create</button>
    </div>
</form>
