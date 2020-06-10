<tr class="hover:bg-gray-100">
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <h3 class="text-lg font-bold mr-2">
            {{ ucwords(str_replace('_', ' ', $role->name)) }}
        </h3>
        <h4 class="text-sm font-semibold text-gray-600 mr-2">{{ $role->guard_name }}</h4>
    </td>
    <td class="px-6 py-4 border-b border-gray-200 text-gray-600">
        {{ Str::limit($role->permissions->implode(', '), 250) }}
    </td>
    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
        <div class="flex space-x-2">
            @canany(['view','update'], Spatie\Permission\Models\Role::class)
            <a href="{{ route('staff.roles.show', $role) }}" class="group p-1 hover:text-blue-600">
                <x-feather-icon name="eye"
                    class="h-5 transition-transform duration-300 transform group-hover:scale-110">View</x-feather-icon>
            </a>
            @endcanany
            @can('delete', $role)
            <form action="{{ route('staff.roles.destroy', $role) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete role?');">
                @csrf_token @method('delete')
                <button type="submit" class="group p-1 hover:text-red-700">
                    <x-feather-icon class="h-5 transition-transform duration-300 transform group-hover:scale-110"
                        name="trash-2">Trash</x-feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </td>
</tr>
