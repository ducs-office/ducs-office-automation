@extends('layouts.master')
@section('body')
<div class="m-6 pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Programmes</h1>
        @can('create', App\Models\Programme::class)
        <a href="{{route('staff.programmes.create')}}" class="btn btn-magenta is-sm shadow-inset">
            New
        </a>
        @endcan
    </div>
    @can('update', App\Models\Programme::class)
    @include('staff.programmes.modals.edit',[
        'modalName' => 'edit-programme-modal'
    ])
    @endcan
    <div class="space-y-5">
        @foreach ($programmes as $index => $programme)
            <div class="p-6 page-card">
                <div class="flex items-baseline justify-between">
                    <div class="flex items-center">
                        <h2 class="text-lg font-bold">
                            {{ ucwords($programme->name) }}
                        </h2>
                        <span class="ml-2 px-3 py-1 rounded bg-black font-bold font-mono text-sm text-white mr-2 text-center">{{ $programme->code }}</span>
                    </div>
                    <div class="flex">
                        @can('update', $programme)
                        <button class="p-1 hover:text-blue-500 mr-1"
                        @click.prevent="$modal.show('edit-programme-modal',{
                            programme: {{ $programme->toJson()}}
                        })">
                            <x-feather-icon class="h-current" name="edit">Edit</x-feather-icon>
                        </button>
                        @endcan
                        @can('delete', App\Models\Programme::class)
                        <form action="{{ route('staff.programmes.destroy', $programme) }}" method="POST"
                            onsubmit="return confirm('Do you really want to delete programme?');">
                            @csrf_token @method('delete')
                            <button type="submit" class="p-1 hover:text-red-700">
                                <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                <h3 class="italic mb-4">
                    {{ $programme->type }}
                </h3>
                <p class="mb-1"><span class="italic font-bold">Duration:</span> {{ $programme->duration }} year(s)</p>

                <div class="flex items-center">
                    <div class="relative z-10 -ml-8 my-4">
                        <h5 class="relative z-20 pl-8 pr-4 py-2 inline-block font-bold bg-magenta-700 text-white shadow">
                            Latest revision (w.e.f {{ $programme->latestRevision->revised_at->format('Y') }})
                        </h5>
                        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
                        </svg>
                    </div>
                    @can('create', App\Models\ProgrammeRevision::class)
                    <div class="ml-auto">
                        <a href="{{ route('staff.programmes.revisions.create', $programme )}}"
                            class="btn btn-magenta is-sm shadow-inset mr-2">
                            New Revision
                        </a>
                    </div>
                    @endcan
                </div>
                <details class="overflow-hidden mt-3">
                    <summary class="p-2 bg-gray-300 cursor-pointer outline-none rounded">
                        Courses
                    </summary>
                    <div class="ml-2 border-l p-4 grid grid-cols-3 gap-4 items-start">
                        @forelse (range(1, $programme->duration*2) as $semester)
                            <div class="border border-gray-600 rounded-lg overflow-hidden">
                                <div class="px-4 py-2 text-center border-b border-gray-600 bg-gray-300">
                                    <h5 class="font-bold"> Semester {{ $semester }}</h5>
                                </div>
                                <ul class="divide-y">
                                    @forelse ($groupedCourses[$index][$semester] ?? [] as $course)
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
                </details>
                <div class="mt-2">
                    <a href="{{ route('staff.programmes.revisions.show', $programme) }}"
                        class=" text-magenta-600 underline">
                        Show all revisions
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
