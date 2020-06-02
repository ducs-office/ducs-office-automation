@extends('layouts.master')
@push('modals')
    <x-modal name="create-role-modal" class="page-card p-6" :open="! $errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8">Create Role</h2>
        @include('_partials.forms.create-role')
    </x-modal>
    <livewire:edit-role-modal />
@endpush
@section('body')
<div class="page-card m-6">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Roles & Permissions</h1>
        @can('create', Spatie\Permission\Models\Role::class)
            <x-modal.trigger modal="create-role-modal"
                class="btn btn-magenta is-sm shadow-inner">
                Add New Role
            </x-modal.trigger>
        @endcan
    </div>
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
                return explode(':', $p->name, 2)[0];
            }) as $group => $gPermissions)
            <tr>
                <th class="text-left font-bold px-2 py-1"> {{ ucwords($group) }}: </th>
                <td class="text-left px-2 py-1">
                    @foreach ($gPermissions as $permission)
                    <span class="m-1 bg-blue-500 text-white py-1 px-2 rounded text-sm font-bold leading-none">
                        {{ ucwords(explode(':', $permission->name, 2)[1]) }}
                    </span>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </table>
        <div class="ml-auto px-2 flex items-center">
            @can('update', Spatie\Permission\Models\Role::class)
            <x-modal.trigger modal="edit-role-modal"
                :livewire="['payload' => $role->id]"
                class="p-1 hover:text-red-700 mr-2">
                <x-feather-icon name="edit" class="h-current">Edit</x-feather-icon>
            </x-modal.trigger>
            @endcan
            @can('delete', $role)
            <form action="{{ route('staff.roles.destroy', $role) }}" method="POST"
                onsubmit="return confirm('Do you really want to delete role?');">
                @csrf_token @method('delete')
                <button type="submit" class="p-1 hover:text-red-700">
                    <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                </button>
            </form>
            @endcan
        </div>
    </div>
    @empty
    <div class="py-8 flex flex-col items-center justify-center text-gray-500">
        <x-feather-icon name="frown" class="h-16"></x-feather-icon>
        <p class="mt-4 mb-2  font-bold">
            Sorry! No Roles added yet.
        </p>
    </div>
    @endforelse
</div>
@endsection
