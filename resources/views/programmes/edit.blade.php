@extends('layouts.master')
@section('body')
<div class="page-card max-w-xl my-4 mx-auto">
    <h2 class="page-header px-6">Update Programme</h2>
    <form action="{{ route('programmes.update', $programme) }}" method="POST" class="px-6">
        @csrf_token @method('PATCH')
        <div class="mb-2">
            <label for="programme_code" class="w-full form-label">Code<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_code" type="text" name="code" class="w-full form-input" value="{{ old('code', $programme->code) }}">
        </div>
        <div class="mb-2">
            <label for="programme_wef" class="w-full form-label">Date (w.e.f)<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_wef" type="date" name="wef" class="w-full form-input" value="{{ old('wef', $programme->wef) }}">
        </div>
        <div class="mb-2">
            <label for="programme_name" class="w-full form-label">Name<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_name" type="text" name="name" class="w-full form-input" value="{{ old('name', $programme->name) }}">
        </div>
        <div class="mb-2">
            <label for="programme_type" class="w-full form-label">Type<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <select class="w-full form-input" name="type" required>
                <option value="Under Graduate(U.G.)"
                    {{ old('type', $programme->type) === 'Under Graduate(U.G.)' ? 'selected' : ''}}>Under
                    Graduate(U.G.)</option>
                <option value="Post Graduate(P.G.)"
                    {{ old('type', $programme->type) === 'Post Graduate(P.G.)' ? 'selected' : ''}}>Post
                    Graduate(P.G.)</option>
            </select>
        </div>
        <course-sections inline-template
            :duration="{{ $programme->duration }}"
            :semester-courses="{{ $programme->courses->groupBy('pivot.semester')->map->pluck('id')->toJson() }}">
            <div class="relative mb-2">
                <div class="mb-2">
                    <label for="programme_duration" class="w-full form-label">Duration (years)<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_duration" type="number" name="duration" class="w-full form-input" v-model="years">
                </div>
                <transition name="flip">
                    <div class="flex flex-wrap mb-2 -mx-2" v-if="semesters > 0">
                        <div v-for="(semester,index) in semesters" :key="index"
                        class="w-1/2 px-2 py-1">
                            <label class="w-full form-label">Semester @{{semester}}: Courses</label>
                            <v-multi-typeahead
                                :name="`semester_courses[${index}][]`"
                                source="/api/courses"
                                find-source="/api/courses/{value}"
                                limit="5"
                                :value="semester in courses ? courses[semester] : []"
                                placeholder="Courses"
                                >
                            </v-multi-typeahead>
                        </div>
                    </div>
                </transition>
            </div>
        </course-sections>
        <div class="mb-2">
            <button type="submit" class="btn btn-magenta">Update</button>
        </div>
    </form>
</div>
@endsection


