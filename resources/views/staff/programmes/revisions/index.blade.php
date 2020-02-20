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
        <div class="flex flex-wrap w-full px-6">
            @foreach ($programme->revisions as $index => $programmeRevision)
                <div class="page-card w-5/12 border-b mb-4 p-6 {{ $loop->index % 2 === 1 ? 'ml-10': ''}}">
                    <div class="flex items-baseline">
                        <div class="relative z-10 -ml-8 my-4">
                            <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">Revision w.e.f {{ $programmeRevision->revised_at->format('Y') }}</h5>
                            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                            </svg>
                        </div>
                        <div class="ml-auto flex">
                            <a href="{{ route('staff.programmes.revisions.edit', [$programme, $programmeRevision]) }}"
                                class="p-1 text-gray-700 hover:text-blue-600 hover:bg-gray-200 rounded mr-3" title="Edit">
                                <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                            </a>
                            <form method="POST" action="{{  route('staff.programmes.revisions.destroy', [
                                        'programme' => $programme,
                                        'revision' => $programmeRevision
                                    ]) }}"
                                onsubmit="return confirm('Do you really want to delete programme revision?');">
                                @csrf_token @method('delete')
                                <button type="submit" class="p-1 hover:text-red-700">
                                    <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-8">
                        @forelse (range(1, $programme->duration*2) as $semester)
                            <div class="w-1/2 border-t border-r border-gray-900 {{ $semester % 2 === 1 ? 'border-l': ''}} {{ $semester > ($programme->duration-1) * 2 ? 'border-b': '' }}">
                                <div class="px-4 py-2 text-center border-b border-gray-900 bg-gray-300">
                                    <h5 class="font-bold"> Semester {{ $semester }}</h5>
                                </div>
                                <ul class="ml-6 p-4 list-disc">
                                    @forelse ($groupedRevisionCourses[$index][$semester] ?? [] as $course)
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
    </div>
@endsection
