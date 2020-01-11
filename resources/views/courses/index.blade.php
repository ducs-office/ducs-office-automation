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
            ])
            @endcan
        </div>
        @can('update', App\Course::class)
        @include('courses.modals.edit', [
            'modalName' => 'edit-course-modal'
        ])
        @endcan
        <div>
            @foreach ($courses as $course)
                <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
                    <div class="flex items-baseline justify-center">
                        <span class="px-2 py-1 rounded text-xs uppercase text-white bg-black mr-2 font-bold w-18">
                            {{ $course->type === 'Core' ? 'Core' : ($course->type === 'General Elective' ? 'G E ' : 'O E ') }}
                        </span>
                        <h4 class="font-bold text-sm text-gray-600 w-24">{{ $course->code }}</h4>
                        <h3 class="font-bold text-lg capitalize mr-2">{{ $course->name }}</h3>
                        @if ($course->programmes->count() > 0)
                            <p class="text-gray-500 truncate">{{ $course->programmes->pluck('code')->implode(', ')}}</p>
                        @endif
                        <div class="flex flex-wrap mx-2">
                            @foreach ($course->attachments as $attachment)
                                <div class="inline-flex items-center px-2 rounded border hover:bg-gray-300 text-gray-600 mx-2">
                                    <a href="{{ route('attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                                        <feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Syllabus</feather-icon>
                                        <span>{{ $attachment->original_name }}</span>
                                    </a>
                                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" onsubmit= "return confirm('Do you really want to delete attachment?'); ">
                                        @csrf_token @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-red-500 hover:text-white">
                                            <feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</feather-icon>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center">
                        @can('update', App\Course::class)
                        <button class="p-1 hover:text-blue-500 mr-2"
                        @click.prevent="$modal.show('edit-course-modal', {
                            course: {{ $course->toJson() }}
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
