@extends('layouts.master')
@section('body')
<div class="m-6 page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Academic Courses</h1>
        <a href="/courses/create" class="btn btn-magenta is-sm shadow-inset" @click.prevent="$modal.show('create-course-form')">
            Create
        </a>
    </div>
    <modal name="create-course-form" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">New Course</h2>
            <form action="/courses" method="POST" class="flex items-end">
                @csrf
                <div class="flex-1 mr-2">
                    <label for="course_code" class="w-full form-label">Course Code</label>
                    <input id="course_code" type="text" name="code" class="w-full form-input">
                </div>
                <div class="flex-1 mr-5">
                    <label for="course_name" class="w-full form-label">Course</label>
                    <input id="course_name" type="text" name="name" class="w-full form-input">
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </div>
    </modal>
    <course-update-modal name="course-update-modal">@csrf @method('patch')</course-update-modal>
    @foreach ($courses as $course)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline">
                <h4 class="text-sm font-semibold text-gray-600 mr-2 w-24">{{ $course->code }}</h4>
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($course->name) }}
                </h3>
            </div>
            <div>
                <button class="btn btn-gray" @click.prevent="$modal.show('course-update-modal', {course: {{ $course->toJson() }}})">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
            </div>
        </div>
    @endforeach
    
</div>
@endsection
