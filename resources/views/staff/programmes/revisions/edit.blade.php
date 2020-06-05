@extends('layouts.master')
@section('body')
<div class="page-card p-6 my-4 mx-auto">
    <h2 class="text-lg font-bold mb-2">Update Programme Revision</h2>
    <div class="text-gray-600 flex items-center space-x-2 leading-0 mb-8">
        <span
            class="px-4 py-1 rounded-full bg-magenta-100 text-magenta-700 text-xs font-bold tracking-wider">{{ $programme->code }}</span>
        <h6 class="text-sm font-bold">
            {{ ucwords($programme->name) }}
        </h6>
    </div>
    <x-form method="patch" action="{{ route('staff.programmes.revisions.update', [$programme, $revision]) }}">
        <livewire:programme-revision-form
            :programme="$programme"
            :revised-at="$revision->revised_at"
            :semester-courses="$semesterCourses" />
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Update</button>
        </div>
    </x-form>
</div>
@endsection
