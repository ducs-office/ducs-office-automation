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
                    <label for="permissions" class="w-full form-label">Assign Permissions<span class="h-current text-red-500 text-lg">*</span></label>
                    <select id="permissions" name="permissions[]" class="w-full form-input" multiple>
                        @foreach ($permissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
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
        <div class="px-2 flex-1 flex flex-wrap items-center -my-1">
            @foreach ($role->permissions as $permission)
            <span
                class="m-1 bg-blue-500 text-white p-1 rounded text-xs font-bold tracking-wide">{{ $permission->name }}</span>
            @endforeach
        </div>
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
            @can('delete', Spatie\Permission\Models\Role::class)
            <form action="{{ route('roles.destroy', $role) }}" method="POST">
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
