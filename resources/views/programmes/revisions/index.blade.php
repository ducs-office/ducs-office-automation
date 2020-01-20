@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="page-header px-6">
            <h2 class="mb-1">Programme Revisions</h2>
            <div class="flex mt-3">
                <h2 class="text-lg font-bold">
                   {{ ucwords($programme->name) }}
                </h2>
            <span class="ml-2 py-1 rounded bg-black font-bold font-mono text-sm text-white mr-2 w-24 text-center"> {{ $programme->code }}</span>
            </div>
        </div>
        @foreach ($programmeRevisions as $index => $programmeRevision)
            <div class="page-card w-1/2 m-auto border-b mb-4 p-6">
                <div class="flex items-baseline">
                    <div class="relative z-10 -ml-8 my-4">
                        <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">Date :  {{ $programmeRevision->revised_at }}</h5>
                        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                        </svg>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('programme_revision.edit', [$programme, $programmeRevision]) }}">Edit Programme</a>
                        <form action="{{  route('programme_revisions.destroy', [$programme, $programmeRevision]) }}" method="POST"
                            onsubmit="return confirm('Do you really want to delete programme revision?');">
                            @csrf_token @method('delete')
                            <button type="submit" class="p-1 hover:text-red-700">
                                <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="flex flex-wrap mb-8">
                    @forelse ($groupedRevisionCourses[$index] as $semester => $courses)
                        <div class="w-1/2 border-t border-r border-gray-900 {{ $semester % 2 == 1 ? 'border-l': ''}} {{ $semester > ($programme->duration-1) * 2 ? 'border-b': '' }}">
                            <div class="px-4 py-2 text-center border-b border-gray-900 bg-gray-300">
                                <h5 class="font-bold"> Semester {{ $semester }}</h5>
                            </div>
                            <ul class="ml-6 p-4 list-disc">
                                @forelse ($courses as $course)
                                    <li class="mb-1"> {{ $course->name}}</li>
                                @empty
                                    <p class="px-2 py-1 text-gray-700 font-bold">No course in this semster</p>
                                @endforelse
                            </ul>
                        </div>
                    @empty
                        <p class="px-6 py-4 text-gray-700 font-bold"> No course in this semester</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endsection