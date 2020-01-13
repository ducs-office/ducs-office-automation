@extends('layouts.master')
@section('body')
<div class="m-6 page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Academic Programmes</h1>
        @can('create', App\Programme::class)
        <a href="{{route('programmes.create')}}" class="btn btn-magenta is-sm shadow-inset">
            New
        </a>
        @endcan
    </div>
    <v-modal name="view-programme-courses-modal" height="auto">
        <template v-slot="{ data }">
            <div class="p-6">
                <h5 class="text-lg font-bold mb-8 form-label">Courses</h5>
                <div class="flex flex-wrap -mx-2">
                    <div v-for="(course, sem) in data('courses')" :key="sem" class="p-2">
                        <p class="mb-1">Semester @{{ sem }}</p>
                        <ul>
                            <li v-for="sem_course in course"
                            class="w-full bg-gray-200 text-gray-700 px-3 py-2 border border-gray-200 rounded mb-1"
                            :key="sem_course.id" :value="sem_course.id">
                                @verbatim
                                {{sem_course.code}} - {{sem_course.name}}
                                @endverbatim
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </template>
    </v-modal>
    @foreach ($programmes as $programme)
        <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="flex items-baseline justify-between">
                <span class="px-2 py-1 rounded text-xs uppercase text-white bg-blue-600 mr-2 font-bold">
                    {{ $programme->type === 'Under Graduate(U.G.)' ? 'UG' : 'PG' }}
                </span>
                <p class="px-4">{{ $programme->wef }}</p>
                <h4 class="px-4 text-sm font-semibold text-gray-600 mr-2 w-24">{{ $programme->code }}</h4>
                <h3 class="px-4 text-lg font-bold mr-2">
                    {{ ucwords($programme->name) }}
                </h3>
            </div>
            <div class="flex items-baseline">
                <button class="btn btn-magenta is-sm shadow-inset" @click= "
                    $modal.show('view-programme-courses-modal',{
                    courses: {{$programme->courses->groupBy('pivot.semester')->toJson()}}
                })">
                    View Courses
                </button>
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
    @endforeach

</div>
@endsection
