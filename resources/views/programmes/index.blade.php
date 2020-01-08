@extends('layouts.master')
@section('body')
<div class="m-6 page-card pb-0">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Academic Programmes</h1>
        @can('create', App\Programme::class)
        <button class="btn btn-magenta is-sm shadow-inset" @click.prevent="$modal.show('create-programme-form')">
            New
        </button>
        @endcan
    </div>
    @can('create', App\Programme::class)
    <modal name="create-programme-form" height="auto">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">New Programme</h2>
            <form action="{{ route('programmes.store') }}" method="POST" class="px-6">
                @csrf_token
                <div class="mb-2">
                    <label for="programme_code" class="w-full form-label">Programme Code<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input">
                </div>
                <div class="mb-2">
                    <label for="programme_wef" class="w-full form-label">Date (w.e.f)<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_wef" type="date" name="wef" class="w-full form-input">
                </div>
                <div class="mb-2">
                    <label for="programme_name" class="w-full form-label">Programme<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input">
                </div>
                <div class="mb-2">
                    <label for="programme_type" class="w-full form-label">Type<span class="h-current text-red-500 text-lg">*</span></label>
                    <select class="w-full form-input" name="type" required>
                        <option value="Under Graduate(U.G.)" {{ old('type', 'Under Graduate(U.G.') === 'Under Graduate(U.G.)' ? 'selected' : ''}}>Under Graduate(U.G.)</option>
                        <option value="Post Graduate(P.G.)" {{ old('type', 'Post Graduate(P.G.') === 'Post Graduate(P.G.)' ? 'selected' : ''}}>Post Graduate(P.G.)</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="programme_course" class="w-full form-label">Add Courses</label>
                    <div class="overflow-y-auto overflow-x-hidden h-32 border">
                        @foreach ($courses as $course)
                            <div class="flex justify-between mt-1 px-3 py-1">
                                <label for="course-{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</label>
                                <input 
                                    id="course-{{ $course->id }}"
                                    type="checkbox"
                                    name="courses[]"
                                    value="{{ $course->id }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </div>
    </modal>
    @endcan
    @can('update', App\Programme::class)
    <programme-update-modal name="programme-update-modal">@csrf_token @method('patch')</programme-update-modal>
    @endcan
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
            <div class="flex">
                @can('update', App\Programme::class)
                <button class="p-1 hover:text-blue-500 mr-1" @click.prevent="$modal.show('programme-update-modal', {programme: {{ $programme->toJson() }}, courses: {{$programme->courses->merge($courses)->toJson()}}})">
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
    @endforeach

</div>
@endsection
