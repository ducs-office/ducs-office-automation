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
    <form action="{{ route('programme_revision.update', [$programme, $programme_revision]) }}" method="POST" class="px-6">
        @csrf_token @method('PATCH')
        <div class="mb-2">
            <label for="revised_at" class="w-full form-label">Revised At<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="revised_at" type="date" name="revised_at" class="w-full form-input" value="{{ old('wef', $programme_revision->revised_at->format('Y-m-d')) }}">
        </div>
        <course-sections inline-template
            :duration="{{ $programme->duration }}"
            :semester-courses="{{ json_encode($semester_courses) }}">
            <div class="relative mb-2">
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
                                :value="semester in courses ? courses[semester]: []"
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