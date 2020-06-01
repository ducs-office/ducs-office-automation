@extends('layouts.master')
@section('body')
    <div class="m-6">
        <div class="page-header px-6 mb-4">
            <h2>Programme Revisions</h2>
        </div>
        <div class="flex items-center mb-6 px-6">
            <h3 class="text-lg font-bold">
                {{ ucwords($programme->name) }}
            </h3>
            <span class="ml-2 py-2 px-4 rounded bg-black font-bold font-mono text-sm text-white mr-2 text-center leading-none"> {{ $programme->code }}</span>
            @can('create', App\Models\ProgrammeRevision::class)
                <a href="{{ route('staff.programmes.revisions.create', $programme )}}"
                    class="ml-auto btn btn-magenta is-sm shadow-inset mr-2">
                    New Revision
                </a>
            @endcan
        </div>
        <div class="grid grid-cols-1 gap-10">
            @foreach ($programme->revisions as $programmeIndex => $programmeRevision)
                <div class="page-card p-6 pt-0">
                    <div class="flex items-center mb-6">
                        <div class="relative z-10 -ml-8">
                            <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow rounded-br-lg">Revision w.e.f {{ $programmeRevision->revised_at->format('Y') }}</h5>
                            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                            </svg>
                        </div>
                        <div class="ml-auto flex items-center self-end space-x-3">
                            @can('update', $programmeRevision)
                            <a href="{{ route('staff.programmes.revisions.edit', [$programme, $programmeRevision]) }}"
                                class="p-1 text-gray-700 hover:text-blue-600 hover:bg-gray-200 rounded" title="Edit">
                                <x-feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</x-feather-icon>
                            </a>
                            @endcan
                            @can('delete', $programmeRevision)
                            <form method="POST" action="{{  route('staff.programmes.revisions.destroy', [
                                        'programme' => $programme,
                                        'revision' => $programmeRevision
                                    ]) }}"
                                onsubmit="return confirm('Do you really want to delete programme revision?');">
                                @csrf_token @method('delete')
                                <button type="submit" class="p-1 hover:text-red-700">
                                    <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 items-start">
                        @forelse (range(1, $programme->duration*2) as $semester)
                            <div class="border border-gray-600 rounded-lg overflow-hidden">
                                <div class="px-4 py-2 text-center border-b border-gray-600 bg-gray-300">
                                    <h5 class="font-bold"> Semester {{ $semester }}</h5>
                                </div>
                                <ul class="divide-y">
                                    @forelse ($groupedRevisionCourses[$programmeIndex][$semester] ?? [] as $course)
                                        <li class="px-4 py-2 border-gray-600 flex items-center">
                                            <span class="inline-block px-3 py-1 font-bold rounded-full text-xs font-mono bg-gray-200 text-gray-800 mr-2">{{ $course->code }}</span>
                                            {{ $course->name}}
                                        </li>
                                    @empty
                                        <li class="px-4 py-2 text-gray-700 font-bold">No course in this semster</li>
                                    @endforelse
                                </ul>
                            </div>
                        @empty
                            <p class="py-4 text-gray-700 font-bold"> No course in this semester</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
