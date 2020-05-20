@extends('layouts.master')
@push('modals')
    <x-modal name="create-user-modal" class="p-6 min-w-1/2">
        <h2 class="text-lg font-bold mb-8">Create Users</h2>
        @include('_partials.forms.create-user')
    </x-modal>
    <livewire:edit-user-modal :roles="$roles" />
@endpush
@section('body')
<div class="page-card m-2">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Users</h1>
        @can('create', App\Models\User::class)
        <x-modal.trigger modal="create-user-modal"
            class="btn btn-magenta is-sm shadow-inner">
            New
        </x-modal.trigger>
        @endcan
    </div>
    <table class="min-w-full">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Name
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Category
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Title
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Roles
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @include('_partials.list-items.user')
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-gray-500">
                        <x-feather-icon name="frown" class="w-16 mx-auto"></x-feather-icon>
                        <p class="mt-4 text-center font-bold">
                            Sorry! No Users {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
