@extends('layouts.master')
@push('modals')
    <x-modal name="create-course-modal" class="p-6 min-w-1/2" :open="! $errors->default->isEmpty()">
        <h2 class="mb-8 font-bold text-lg">Create New Course</h2>
        @include('_partials.forms.create-course')
    </x-modal>
    <livewire:edit-course-modal :error-bag="$errors->update" :courseTypes="$courseTypes" />
@endpush
@section('body')
    <div class="m-6">
        <div class="flex items-baseline px-6 pb-4">
            <h1 class="page-header mb-0 px-0 mr-4">Courses</h1>
            @can('create', App\Models\Course::class)
            <x-modal.trigger class="btn btn-magenta is-sm shadow-inner" modal="create-course-modal">
                New
            </x-modal.trigger>
            @endcan
        </div>
        <div class="space-y-5 leading-none">
            @foreach ($courses as $course)
                <div class="relative p-6 page-card">
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 rounded text-sm uppercase text-white bg-black font-bold font-mono">
                            {{ $course->type }}
                        </span>
                        <span class="font-bold text-gray-700 ml-2">{{ $course->code }}</span>
                    </div>
                    <h3 class="font-bold text-lg capitalize mb-4">{{ $course->name }}</h3>
                    @if($latestRevision = $course->revisions->shift())
                    <div class="leading-none my-4">
                        <div class="flex items-center mb-1">
                            <h5 class="font-medium">Latest Revision w.e.f <strong>{{ $latestRevision->revised_at->format('M, Y') }}</strong></h5>
                            <form method="POST" action="{{ route('staff.courses.revisions.destroy', [
                                'course' => $course,
                                'revision' => $latestRevision
                            ]) }}" class="ml-2">
                                @csrf_token @method('DELETE')
                                <button type="submit" class="p-2 text-sm text-gray-700 hover:text-red-600 hover:bg-gray-300 rounded">
                                    <x-feather-icon name="trash-2" class="h-current"></x-feather-icon>
                                </button>
                            </form>
                        </div>
                        <div class="flex flex-wrap -mx-2 -my-1">
                            @foreach ($latestRevision->attachments as $attachment)
                            <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                                @can('view', $attachment)
                                <a href="{{ route('staff.attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                                    <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</x-feather-icon>
                                    <span>{{ $attachment->original_name }}</span>
                                </a>
                                @endcan
                                @can('delete', $attachment)
                                <button type="submit" form="remove-attachment" formaction="{{ route('staff.attachments.destroy', $attachment) }}"
                                    class="p-1 rounded hover:bg-red-500 hover:text-white">
                                    <x-feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</x-feather-icon>
                                </button>
                                @endcan
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <details class="bg-gray-100 rounded border overflow-hidden mb-1">
                        <summary class="p-2 bg-gray-200 cursor-pointer outline-none">
                            <span class="mr-2">Add New Revision</span>
                        </summary>
                        <form action="{{ route('staff.courses.revisions.store', $course) }}"
                            method="POST" class="p-4"
                            enctype="multipart/form-data">
                            @csrf_token
                            <div class="flex items-end mb-2">
                                <div class="flex-1 mr-1">
                                    <label for="revision_date" class="w-full form-label mb-1">
                                        Revision Date <span class="text-red-600">*</span>
                                    </label>
                                    <input type="date" name="revised_at" id="revision_date" class="w-full form-input">
                                </div>
                                <div class="space-y-1">
                                    <label for="course_attachments" 
                                        class="w-full form-label">
                                        Upload Syllabus <span class="text-red-600">*</span>
                                    </label>
                                    <input type="file" id="course_attachments" name="attachments[]"
                                        class="w-full form-input  inline-flex items-center"
                                        tabindex="0"
                                        accept="application/pdf, image/*"
                                        placeholder="select multiple files"
                                        multiple 
                                        required>
                                    </input>
                                </div>
                                <div class="ml-1 flex-shrink-0">
                                    <button type="submit" class="btn btn-magenta px-6">Add</button>
                                </div>
                            </div>
                        </form>
                    </details>
                    <details class="bg-gray-100 rounded border overflow-hidden">
                        <summary class="p-2 bg-gray-200 cursor-pointer outline-none">
                            <span class="mr-2">Older Revisions</span>
                        </summary>
                        <ul class="p-4 list-disc ml-4">
                            @forelse($course->revisions as $courseRevision)
                            <li>
                                <div class="flex items-center mb-1">
                                    <h5 class="font-medium">Revision w.e.f <strong>{{ $courseRevision->revised_at->format('M, Y') }}</strong></h5>
                                    <form method="POST" action="{{ route('staff.courses.revisions.destroy', ([
                                        'course' => $course,
                                        'revision' => $courseRevision
                                        ])) }}"
                                        class="ml-2">
                                        @csrf_token @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-700 hover:text-red-600 hover:bg-gray-300 rounded">
                                            <x-feather-icon name="trash-2" class="h-current"></x-feather-icon>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex flex-wrap -mx-2">
                                    @foreach ($courseRevision->attachments as $attachment)
                                        <div class="inline-flex items-center p-2 rounded border hover:bg-gray-300 text-gray-600 mx-2 my-1">
                                            @can('view', $attachment)
                                            <a href="{{ route('staff.attachments.show', $attachment) }}" target="__blank" class="inline-flex items-center mr-1">
                                                <x-feather-icon name="paperclip" class="h-4 mr-2" stroke-width="2">View Attachment</x-feather-icon>
                                                <span>{{ $attachment->original_name }}</span>
                                            </a>
                                            @endcan
                                            @can('delete', $attachment)
                                            <button type="submit" form="remove-attachment" formaction="{{ route('staff.attachments.destroy', $attachment) }}"
                                                class="p-1 rounded hover:bg-red-500 hover:text-white">
                                                <x-feather-icon name="x" class="h-4" stroke-width="2">Delete Attachment</x-feather-icon>
                                            </button>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                            @empty
                            <p class="p-4 text-gray-600 font-bold">No older Revisions.</p>
                            @endforelse
                        </ul>
                    </details>
                    <div class="absolute top-0 right-0 mt-4 mr-4 flex items-center">
                        @can('update', App\Models\Course::class)
                        <x-modal.trigger :livewire="['payload' => $course->id]" modal="edit-course-modal" title="Edit"
                            class="p-1 text-gray-700 font-bold hover:text-blue-600 transition duration-300 transform hover:scale-110">
                            <x-feather-icon class="h-5" name="edit-3">Edit</x-feather-icon>
                        </x-modal.trigger>
                        @endcan
                        @can('delete', App\Models\Course::class)
                        <form action="{{ route('staff.courses.destroy', $course) }}" method="POST"
                            onsubmit="return confirm('Do you really want to delete course?');">
                            @csrf_token
                            @method('DELETE')
                            <button type="submit" class="p-1 hover:text-red-700">
                                <x-feather-icon name="trash-2" class="h-current">Delete</x-feather-icon>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <form id="remove-attachment"
    method="POST"
    onsubmit="return confirm('Do you really want to delete attachment?');">
    @csrf_token @method('DELETE')
    </form>
@endsection
