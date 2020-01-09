@extends('layouts.master')
@section('body')
    <div class="m-6 page-card">
        <div class="flex items-baseline px-6 pb-4 border-b">
            <h1 class="page-header mb-0 px-0 mr-4">Programme Courses</h1>
            @can('create', App\Course::class)
            <button class="btn btn-magenta is-sm shadow-inset" @click="$modal.show('create-courses-modal')">
                New
            </button>
            @include('courses.modals.create', [
                'modalName' => 'create-college-modal',
                'programmes' => $programmes
            ])
            @endcan
        </div>
        @can('update', App\Course::class)
        @include('courses.modals.edit', [
            'modalName' => 'edit-course-modal',
            'programmes' => $programmes
        ])
        @endcan
        <div>
            @foreach ($courses as $course)
                <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
                    <div class="flex items-baseline">
                        <h4 class="font-bold text-sm text-gray-600 w-24">{{ $course->code }}</h4>
                        <h3 class="font-bold text-lg capitalize mr-2">{{ $course->name }}</h3>
                        <p class="text-gray-500 truncate">{{ ucwords($course->programme->name) }} ({{ $course->programme->code }})</p>
                    </div>
                    <div class="flex items-center">
                        @can('update', App\Course::class)
                        <button class="p-1 hover:text-blue-500 mr-2"
                        @click.prevent="$modal.show('course-update-modal', {
                            course: {{ $course->toJson() }},
                            programmes: {{ $programmes->toJson() }}
                        })">
                            <feather-icon name="edit" class="h-current">Edit</feather-icon>
                        </button>
                        @endcan
                        @can('delete', App\Course::class)
                        <form action="{{ route('courses.destroy', $course) }}" method="POST"
                            onsubmit="return confirm('Do you really want to delete course?');">
                            @csrf_token
                            @method('DELETE')
                            <button type="submit" class="p-1 hover:text-red-700">
                                <feather-icon name="trash-2" class="h-current">Delete</feather-icon>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
