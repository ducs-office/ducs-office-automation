@extends('layouts.master')
@section('body')
    <div class="m-6 page-card">
        <div class="flex items-baseline px-6 pb-4 border-b">
            <h1 class="page-header mb-0 px-0 mr-4">Programme Courses</h1>
            @can('create', App\Course::class)
            <button class="btn btn-magenta is-sm shadow-inset" @click="$modal.show('create-courses-modal')">
                New
            </button>
            @endcan
        </div>
        @can('update', App\Course::class)
        <course-update-modal name="course-update-modal">@csrf_token @method('patch')</course-update-modal>
        @endcan
        @can('create', App\Course::class)
        <modal name="create-courses-modal" height="auto">
            <form action="{{ route('courses.index') }}" method="POST" class="p-6">
                <h2 class="mb-8 font-bold text-lg">Create New Course</h2>
                @csrf_token
                <div class="mb-2">
                    <label for="unique-course-code" class="w-full form-label mb-1">Unique Course Code<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="unique-course-code" name="code" type="text" class="w-full form-input" placeholder="e.g. 4234201">
                </div>
                <div class="mb-2">
                    <label for="course-name" class="w-full form-label mb-1">Course Name<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="course-name" type="text" name="name" class="w-full form-input" placeholder="e.g. Artificial Intelligence">
                </div>
                <div class="mb-2">
                    <label for="course-programme" class="w-full form-label mb-1">Programme<span class="h-current text-red-500 text-lg">*</span></label>
                    <select id="course-programme" name="programme_id" class="w-full form-input">
                        <option value="" selected disabled>-- Select a Programme --</option>
                        @foreach ($programmes as $id => $programme)
                            <option value="{{ $id }}">{{ $programme }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 mb-3">
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </modal>
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
