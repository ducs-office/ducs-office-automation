@extends('layouts.master')
@push('modals')
    <x-modal name="create-phd-course-modal" class="p-6 w-1/2" :open="! $errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8">Create Pre-PhD Course</h2>
        @include('_partials.forms.create-phd-course')
    </x-modal>
    <livewire:edit-phd-course-modal :error-bag="$errors->update" />
@endpush
@section('body')
    <div class="page-card p-0">
        <div class="flex items-center px-6 py-4 border-b">
            <h1 class="page-header mb-0 px-0 mr-4">Pre-PhD Courses</h1>
            @can('create', App\Models\PhdCourse::class)
            <x-modal.trigger modal="create-phd-course-modal"
                class="btn btn-magenta is-sm shadow-inset">
                New
            </x-modal.trigger>
            @endcan
        </div>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">
                        Code
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    @include('_partials.list-items.phd-course')
                @endforeach
            </tbody>
        </table>
        <div class="space-y-1 py-4 px-6">
            {{ $courses->links()  }}
        </div>
    </div>
@endsection
