@extends('layouts.master')
@push('modals')
    <x-modal name="create-user-modal" class="p-6 min-w-1/2" :open="! $errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8">Create Users</h2>
        @include('_partials.forms.create-user')
    </x-modal>
    <livewire:edit-user-modal :error-bag="$errors->update" :roles="$roles" />
@endpush
@section('body')
<div class="page-card p-0">
    <div class="flex items-center px-6 py-2 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Users</h1>
        @can('create', App\Models\User::class)
        <x-modal.trigger modal="create-user-modal"
            class="btn btn-magenta is-sm shadow-inner">
            New
        </x-modal.trigger>
        @endcan
        <form class="ml-auto px-6" x-data>
            <label for="category-filter" class="w-full form-label mb-1">Filter By Category</label>
            <select id="category-filter" name="filters[category]" x-on:input="
                        if ($event.target.value === 'all') {
                            return window.location.replace(window.location.pathname);
                        }
                        return $el.submit();" class="w-full form-select">
                <option @if(request('filters.category', 'all' )=='all' ) selected @endif value="all">All</option>
                @foreach($categories as $category)
                <option @if(request('filters.category', 'all' )==$category) selected @endif value="{{ $category }}">
                    {{ $category }}</option>
                @endforeach
            </select>
        </form>
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
