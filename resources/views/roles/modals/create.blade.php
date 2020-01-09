<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Create Role</h2>
        <form action="{{ route('roles.store') }}" method="POST" class="px-6">
            @csrf_token
            <div class="mb-2">
                <label for="name" class="w-full form-label">Role Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="name" type="text" name="name" class="w-full form-input"
                    placeholder="Enter a name for the role..." required>
            </div>
            <div class="mb-2">
                <label for="permissions" class="w-full form-label">Assign Permissions <span
                        class="h-current text-red-500 text-lg">*</span></label>
                <table>
                    @foreach ($permissions as $group => $gPermissions)
                    <tr class="py-1">
                        <th class="px-2">{{ $group }}:</th>
                        <td class="px-2">
                            @foreach ($gPermissions as $permission)
                            <label for="permission-{{ $permission->id }}"
                                class="px-2 py-1 border rounded inline-flex items-center mr-3">
                                <input id="permission-{{ $permission->id }}" type="checkbox" name="permissions[]"
                                    class="mr-1" value="{{ $permission->id }}">
                                <span>{{ explode(':', $permission->name, 2)[1] }}</span>
                            </label>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="mt-5">
                <button class="btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
</v-modal>
