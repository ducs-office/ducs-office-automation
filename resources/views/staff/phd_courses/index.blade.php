@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="flex items-baseline px-6 pb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Pre-PhD Courses</h1>
            @can('create', App\Models\PhdCourse::class)
            <button class="btn btn-magenta is-sm shadow-inset" @click="$modal.show('create-courses-modal')">
                New
            </button>
            @include('staff.phd_courses.modals.create', [
                'modalName' => 'create-college-modal',
                'courseTypes' => $courseTypes,
            ])
            @endcan
        </div>
        @can('update', App\Models\PhdCourse::class)
        @include('staff.phd_courses.modals.edit', [
            'modalName' => 'edit-course-modal',
            'courseTypes' => $courseTypes,
        ])
        @endcan
        <div>
            @foreach ($courses as $course)
                <div class="relative px-6 py-4 page-card shadow hover:bg-gray-100 hover:shadow-lg mb-2 leading-none flex items-center">
                    <span class="px-2 py-1 rounded text-sm uppercase text-white bg-black font-bold font-mono">
                        {{ $course->type }}
                    </span>
                    <span class="font-bold text-gray-700 ml-2">{{ $course->code }}</span>
                    <h3 class="font-bold text-lg capitalize mx-4">{{ $course->name }}</h3>

                    <div class="ml-auto flex items-center">
                        @can('update', App\Models\PhdCourse::class)
                        <button class="p-1 hover:text-blue-500 mr-2"
                        @click.prevent="$modal.show('edit-course-modal', {
                            course: {{ $course->toJson() }}
                        })">
                            <feather-icon name="edit" class="h-current">Edit</feather-icon>
                        </button>
                        @endcan
                        @can('delete', App\Models\PhdCourse::class)
                        <form action="{{ route('staff.phd_courses.destroy', $course) }}" method="POST"
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
