@extends('layouts.master')
@section('body')
<div class="page-card max-w-xl my-4 mx-auto">
    <div class="page-header px-6">
        <h2 class="mb-1">Edit Programme Revision</h2>
        <div class="flex mt-3">
            <h2 class="text-lg font-bold">
                {{ ucwords($programme->name) }}
            </h2>
            <span class="ml-2 py-1 rounded bg-black font-bold font-mono text-sm text-white mr-2 w-24 text-center">{{ $programme->code }}</span>
        </div>
    </div>
    <form action="{{ route('staff.programmes.revisions.update', [$programme, $revision]) }}" method="POST" class="px-6">
        @csrf_token @method('PATCH')
        <div class="mb-2">
            <label for="revised_at" class="w-full form-label">Revised At<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="revised_at" type="date" name="revised_at" class="w-full form-input" value="{{ old('wef', $revision->revised_at->format('Y-m-d')) }}">
        </div>
        <div class="relative z-10 -ml-8 my-4">
            <h5 class="z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow-md">
                Semester-wise Courses
            </h5>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <p class="text-gray-700 text-sm mb-3">Drag n drop courses to Semester sections.</p>
        <semester-wise-courses-input class="mb-3" name="semester_courses" :count="{{ $programme->duration * 2 }}"
            :data-courses="{{ $courses->toJson() }}" :value="{{ json_encode(old('semester_courses', $semesterCourses)) }}">
        </semester-wise-courses-input>
        <div class="mb-2">
            <button type="submit" class="btn btn-magenta">Update</button>
        </div>
    </form>
</div>
@endsection
