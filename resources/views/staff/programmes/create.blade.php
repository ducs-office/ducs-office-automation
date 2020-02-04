@extends('layouts.master')
@section('body')
<div class="page-card max-w-2xl my-4 mx-auto">
    <h2 class="page-header">New Programme</h2>
    <programme-form :old="{{ json_encode(old()) }}" inline-template>
        <form action="{{ route('staff.programmes.store') }}" method="POST" class="px-6">
        @csrf_token
        <div class="flex flex-wrap mb-2">
            <div class="w-48 mr-1">
                <label for="programme_code" class="w-full form-label">Code<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_code" type="text" name="code" class="w-full form-input" v-model="form.code">
            </div>
            <div class="flex-1 ml-1">
                <label for="programme_wef" class="w-full form-label">Date (w.e.f)<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_wef" type="date" name="wef" class="w-full form-input" v-model="form.wef">
            </div>
        </div>
        <div class="mb-2">
            <label for="programme_name" class="w-full form-label">Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_name" type="text" name="name" class="w-full form-input" v-model="form.name">
        </div>
        <div class="flex mb-8">
            <div class="flex-1 mr-1">
                <label for="programme_type" class="w-full form-label">Type<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <select class="w-full form-input" name="type" required>
                    <option value="" disabled :selected="form.type==''"> -- Programme Type -- </option>
                    <option value="UG" :selected="form.type=='UG'">Under Graduate(U.G.)</option>
                    <option value="PG" :selected="form.type=='PG'">Post Graduate(P.G.)</option>
                </select>
            </div>
            <div class="w-48 ml-1">
                <label for="programme_duration" class="w-full form-label">Duration (years)<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_duration" type="number" name="duration" class="w-full form-input" v-model="form.duration" min="1">
            </div>
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
        <semester-wise-courses-input class="mb-3"
            name="semester_courses"
            :count="form.duration <= 5 ? form.duration * 2 : 10"
            :data-courses="courses"
            v-model="form.semester_courses">
        </semester-wise-courses-input>
        <div class="mt-5">
            <button type="submit" class="btn btn-magenta">Create</button>
        </div>
        </template>
    </programme-form>
</div>
@endsection
