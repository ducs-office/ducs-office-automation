@extends('layouts.master')
@section('body')
<div class="m-6 pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Programmes</h1>
        @can('create', App\Programme::class)
        <a href="{{route('programmes.create')}}" class="btn btn-magenta is-sm shadow-inset">
            New
        </a>
        @endcan
    </div>
    @can('update', App\Programme::class)
    @include('programmes.modals.edit',[
        'modalName' => 'edit-programme-modal'
    ])
    @endcan
    @foreach ($programmes as $index => $programme)
        <div class="p-6 page-card mb-2 hover:bg-gray-100">
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
                        <feather-icon class="h-current" name="edit">Edit</feather-icon>
                    </button>
                    @endcan
                    @can('delete', App\Programme::class)
                    <form action="{{ route('programmes.destroy', $programme) }}" method="POST"
                        onsubmit="return confirm('Do you really want to delete programme?');">
                        @csrf_token @method('delete')
                        <button type="submit" class="p-1 hover:text-red-700">
                            <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            <h3 class="italic mb-4">
                    {{ $programme->type === 'UG' ? 'Under Graduate' : 'Post Graduate' }}
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
                <div class="ml-auto">
                    <a href="{{ route('programme_revision.create', $programme) }}" class="btn btn-magenta is-sm shadow-inset mr-2"> New Revision </a>
                </div>
            </div>
            <details class="bg-gray-100 rounded-t border overflow-hidden mt-3">
                <summary class="p-2 bg-gray-200 cursor-pointer outline-none">
                    Courses
                </summary>
                <div class="flex flex-wrap mx-2">
                    @foreach($grouped_courses[$index] as $semester => $courses)
                        <div class="p-2 w-1/2">
                            <ul class="font-bold mb-2 p-2"> Semester - {{$semester}}
                                @foreach ($courses as $course)
                                    <li class="mx-5 font-normal list-disc">{{$course->name}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </details>
            <div class="mt-2">
                <a href="{{ route('programme_revision.show', $programme) }}" class=" text-magenta-600 underline">Show all revisions</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
