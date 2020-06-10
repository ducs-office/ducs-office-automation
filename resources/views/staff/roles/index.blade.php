@extends('layouts.master')
@section('body')
<div class="page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Roles & Permissions</h1>
        @can('create', Spatie\Permission\Models\Role::class)
            <a href="{{ route('staff.roles.create') }}" class="btn btn-magenta is-sm shadow-inner">
                Add New Role
            </a>
        @endcan
    </div>
    <table class="min-w-full">
        <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Role
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">
                    Permissions
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                @include('_partials.list-items.role')
            @empty
            <tr colspan="3">
                <div class="py-8 flex flex-col items-center justify-center text-gray-500">
                    <x-feather-icon name="frown" class="h-16"></x-feather-icon>
                    <p class="mt-4 mb-2  font-bold">
                        Sorry! No Roles added yet.
                    </p>
                </div>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
