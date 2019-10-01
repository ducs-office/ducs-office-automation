@extends('layouts.master')
@section('body')
<div class="m-6 page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Academic Courses</h1>
        <a href="/courses/create" class="btn btn-magenta is-sm shadow-inset">
            Create
        </a>
    </div>
    @foreach ($courses as $course)
        <div class="px-6 py-2 hover:bg-gray-100 border-b">
            <div class="flex items-baseline">
                <h4 class="text-sm font-semibold text-gray-600 mr-2 w-24">{{ $course->code }}</h4>
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($course->name) }}
                </h3>
            </div>
        </div>
    @endforeach
    
</div>
@endsection
