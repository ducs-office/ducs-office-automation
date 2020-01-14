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
    @foreach ($programmes as $index => $programme)
        <div class="p-6 page-card mb-2 hover:bg-gray-100">
            <div class="flex items-baseline justify-between">
                <div class="flex">
                    <h2 class="text-lg font-bold">
                        {{ ucwords($programme->name) }}
                    </h2>
                    <span class="ml-2 py-1 rounded bg-black font-bold font-mono text-sm text-white mr-2 w-24 text-center">{{ $programme->code }}</span>
                </div>
                <div class="flex">
                    @can('update', $programme)
                    <a class="p-1 hover:text-blue-500 mr-1" href="{{ route('programmes.edit', $programme) }}">
                        <feather-icon class="h-current" name="edit">Edit</feather-icon>
                    </a>
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
            <h3 class="py-1 italic mb-2" >
                    {{ $programme->type === 'UG' ? 'Under Graduate' : 'Post Graduate' }}
            </h3>
            <p class="mb-1"><span class="italic font-bold">Duration:</span> {{ $programme->duration }} year(s)</p>
            <p class="mb-1"><span class="italic font-bold">Date (w.e.f) :</span> {{ $programme->wef }}</p>
            <div class="mt-2">
                <details class="bg-gray-100 rounded-t border overflow-hidden">
                    <summary class="p-2 bg-gray-200 cursor-pointer outline-none">
                        Courses
                    </summary>
                    <div class="flex flex-wrap -mx-2">
                        @foreach($grouped_courses[$index] as $semester => $courses)
                            <div class="p-2 w-1/2">
                                <ul class="font-bold mb-2 p-2"> Semester - {{$semester}}
                                    @foreach ($courses as $course)
                                        <li class="font-normal">{{$course->name}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </details>
            </div>
        </div>
    @endforeach

</div>
@endsection
