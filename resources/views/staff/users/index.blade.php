@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Users</h1>
        @can('create', App\Models\User::class)
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-user-modal')">
            New
        </button>
        @include('staff.users.modals.create', [
            'modalName' => 'create-user-modal',
            'roles' => $roles,
        ])
        @endcan
    </div>
    @can('update', App\Models\User::class)
    @include('staff.users.modals.edit', [
        'modalName' => 'edit-user-modal',
        'roles' => $roles,
    ])
    @endcan
    @forelse($users as $user)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
            <div class="px-2 w-64">
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($user->name) }}
                </h3>
                <h4 class="text-sm font-semibold text-gray-600 mr-2">{{ $user->email }}</h4>
            </div>
            <div class="flex flex-1 flex-wrap items-center">
                @foreach ($user->roles as $role)
                    <span class="mx-1 bg-blue-500 text-white p-1 rounded text-xs font-bold tracking-wide">{{ ucwords(str_replace('_', ' ', $role->name)) }}</span>
                @endforeach
                <span class="mx-1 bg-green-500 text-white p-1 rounded text-xs font-bold tracking-wide">
                    {{ $user->category }}
                </span>
                @if($user->isSupervisor())
                <span class="mx-1 bg-gray-800 text-white py-1 px-2 rounded text-xs font-bold uppercase tracking-wide">
                    Supervisor
                </span>
                @endif
            </div>
            <div class="ml-auto px-2 flex items-center">
                @if(! $user->isSupervisor())
                <form action="{{ route('staff.users.update', $user) }}" method="POST" class="mr-3"
                    onsubmit="return confirm('Caution: This action cannot be undone. Are you sure?');">
                    @csrf_token @method('PATCH')
                    <input type="hidden" name="is_supervisor" value="true">
                    <button class="btn btn-magenta text-sm px-3 py-1"> Make Supervisor</button>
                </form>
                @endif
                @can('update', App\Models\User::class)
                <button type="submit" class="p-1 hover:text-red-700 mr-2"
                    @click="
                        $modal.show('edit-user-modal', {
                            user: {
                                id: {{ $user->id }},
                                name: {{ json_encode($user->name) }},
                                email: {{ json_encode($user->email) }},
                                roles: {{ $user->roles->pluck('id')->toJson() }},
                                category: {{ json_encode($user->category) }}
                            },
                        })">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', $user)
                <form action="{{ route('staff.users.destroy', $user) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete user \'{{ $user->name }}\'?');">
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
                Sorry! No Letters {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
            </p>
        </div>
    @endforelse
</div>
@endsection
