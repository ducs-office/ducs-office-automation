@extends('layouts.master')
@section('body')
    <div class="page-card p-0" @can('update', Spatie\Permission\Models\Role::class)x-data="{editing: false}"@endcan>
        <div class="px-6 py-4 border-b flex items-center">
            <div>
                <h2 class="text-xl font-bold mb-1">Permissions</h2>
                <h5 class="text-gray-600 font-bold">{{ $role->name }}</h5>
            </div>
            @can('update', Spatie\Permission\Models\Role::class)
            <div class="ml-auto">
                <button x-show="! editing" class="group btn btn-magenta is-sm inline-flex space-x-2" x-on:click="editing = true">
                    <x-feather-icon name="edit-3" class="h-6 transition-transform duration-300 transform group-hover:scale-110"></x-feather-icon>
                    <span>Edit Role</span>
                </button>
                <button x-show="editing" class="btn is-sm" x-on:click="editing = false">
                    <span>Cancel</span>
                </button>
            </div>
            @endcan
        </div>
        @can('update', Spatie\Permission\Models\Role::class)
        <template x-if="! editing">
            @include('_partials.show-role')
        </template>
        <div x-show="editing" class="px-6 py-4">
            @include('_partials.forms.edit-role')
        </div>
        @else
            @include('_partials.show-role')
        @endcan
    </div>
@endsection
