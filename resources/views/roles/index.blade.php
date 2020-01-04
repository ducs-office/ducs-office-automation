@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Roles & Permissions</h1>
        @can('create', Spatie\Permission\Models\Role::class)
        <button class="btn btn-magenta is-sm shadow-inner" @click.prevent="$modal.show('create-new-role-form')">
            New
        </button>
        @endcan
    </div>

    @can('create', Spatie\Permission\Models\Role::class)
    <modal name="create-new-role-form" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Create Role</h2>
            <form action="{{ route('roles.store') }}" method="POST" class="px-6">
                @csrf_token
                <div class="mb-2">
                    <label for="name" class="w-full form-label">Role Name<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="name" type="text" name="name" class="w-full form-input"
                        placeholder="Enter a name for the role..." required>
                </div>
                <div class="mb-2">
                    <label for="permissions" class="w-full form-label">Assign Permissions <span class="h-current text-red-500 text-lg">*</span></label>
                    <table>
                        @foreach ($permissions as $group => $gPermissions)
                            <tr class="py-1">
                                <th class="px-2">{{ $group }}:</th>
                                <td class="px-2">
                                    @foreach ($gPermissions as $permission)
                                        <label for="permission-{{ $permission->id }}" class="px-2 py-1 border rounded inline-flex items-center mr-3">
                                            <input id="permission-{{ $permission->id }}"
                                            type="checkbox"
                                            name="permissions[]"
                                            class="mr-1"
                                            value="{{ $permission->id }}">
                                            <span>{{ explode(' ', $permission->name, 2)[0] }}</span>
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
    </modal>
    @endcan
    @can('update', Spatie\Permission\Models\Role::class)
    <role-update-modal name="role-update-modal" :permissions="{{ $permissions->toJson() }}">@csrf_token @method('PATCH')
    </role-update-modal>
    @endcan
    @forelse($roles as $role)
    <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
        <div class="px-2 w-64">
            <h3 class="text-lg font-bold mr-2">
                {{ ucwords(str_replace('_', ' ', $role->name)) }}
            </h3>
            <h4 class="text-sm font-semibold text-gray-600 mr-2">{{ $role->guard_name }}</h4>
        </div>
        <table class="px-2 flex-1 -my-1">
            @foreach ($role->permissions->groupBy(function($p) {
                return explode(' ', $p->name, 2)[1];
            }) as $group => $permissions)
            <tr>
                <th class="text-left font-bold px-2 py-1"> {{ ucwords($group) }}: </th>
                <td class="text-left px-2 py-1">
                    @foreach ($permissions as $permission)
                    <span class="m-1 bg-blue-500 text-white py-1 px-2 rounded text-sm font-bold leading-none">
                        {{ ucwords(explode(' ', $permission->name, 2)[0]) }}
                    </span>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </table>
        <div class="ml-auto px-2 flex items-center">
            @can('update', Spatie\Permission\Models\Role::class)
            <button type="submit" class="p-1 hover:text-red-700 mr-2" @click="
                        $modal.show('role-update-modal', {
                            role: {
                                id: {{ $role->id }},
                                name: '{{ $role->name }}',
                                guard_name: '{{ $role->guard_name }}'
                            },
                            role_permissions: {{ $role->permissions->pluck('id')->toJson() }}
                        })">
                <feather-icon class="h-current" name="edit">Edit</feather-icon>
            </button>
            @endcan
            @can('delete', $role)
            <form action="{{ route('roles.destroy', $role) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete role?');">
                @csrf_token @method('delete')
                <button type="submit" class="p-1 hover:text-red-700">
                    <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="py-8 flex flex-col items-center justify-center text-gray-500">
        <feather-icon name="frown" class="h-16"></feather-icon>
        <p class="mt-4 mb-2  font-bold">
            Sorry! No Roles added yet.
        </p>
    </div>
    @endforelse
</div>
@endsection
