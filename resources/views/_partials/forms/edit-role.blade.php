<form action="{{ route('staff.roles.update', $role) }}" method="POST">
    @csrf_token @method('PATCH')
    <div class="mb-2">
        <label for="name" class="w-full form-label mb-1">Role Name<span
                class="h-current text-red-500 text-lg">*</span></label>
        <input id="name" type="text" name="name" class="w-full form-input"
            value="{{ $role->name }}"
            placeholder="Enter a name for the role..." required>
    </div>
    <div class="mb-5">
        <label for="permissions" class="w-full form-label mb-1">
            Assign Permissions<span class="h-current text-red-500 text-lg">*</span>
        </label>
        <table>
            @foreach ($permissions as $group => $gPermissions)
            <tr class="py-1">
                <th class="px-2 text-left">{{ $group }}:</th>
                <td class="px-2">
                    <x-grouped-permission-input name="permissions[]"
                        :old="old('permissions', $role->permissions->map->id->toArray())"
                        :permissions="$gPermissions">
                    </x-grouped-permission-input>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
