@extends('layouts.master')
@section('body')
    <div class="m-6 page-card">
        <div class="flex items-baseline px-6 pb-4 border-b">
            <h1 class="page-header mb-0 px-0 mr-4">Course Papers</h1>
            <button class="btn btn-magenta is-sm shadow-inset" @click="$modal.show('create-papers-modal')">
                New
            </button>
        </div>
        <paper-update-modal name="paper-update-modal">@csrf @method('patch')</paper-update-modal>
        <modal name="create-papers-modal" height="auto">
            <form action="/papers" method="POST" class="p-6">
                <h2 class="mb-8 font-bold text-lg">Create New Paper</h2>
                @csrf
                <div class="mb-2">
                    <label for="unique-paper-code" class="w-full form-label mb-1">Unique Paper Code</label>
                    <input id="unique-paper-code" name="code" type="text" class="w-full form-input" placeholder="e.g. 4234201">
                </div>
                <div class="mb-2">
                    <label for="paper-name" class="w-full form-label mb-1">Paper Name</label>
                    <input id="paper-name" type="text" name="name" class="w-full form-input" placeholder="e.g. Artificial Intelligence">
                </div>
                <div class="mb-2">
                    <label for="paper-course" class="w-full form-label mb-1">Course</label>
                    <select id="paper-course" name="course_id" class="w-full form-input">
                        <option value="" selected disabled>-- Select a Course --</option>
                        @foreach ($courses as $id => $course)
                            <option value="{{ $id }}">{{ $course }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 mb-3">
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </modal>
        <div>
            @foreach ($papers as $paper)
                <div class="px-6 py-2 hover:bg-gray-100 border-b flex justify-between">
                    <div class="flex items-baseline">
                        <h4 class="font-bold text-sm text-gray-600 w-24">{{ $paper->code }}</h4>
                        <h3 class="font-bold text-lg capitalize mr-2">{{ $paper->name }}</h3>
                        <p class="text-gray-500 truncate">{{ ucwords($paper->course->name) }} ({{ $paper->course->code }})</p>
                    </div>
                    <div class="flex items-center">
                        <button class="p-1 hover:text-blue-500 mr-2"
                        @click.prevent="$modal.show('paper-update-modal', {
                            paper: {{ $paper->toJson() }},
                            courses: {{ $courses->toJson() }}
                        })">
                            <feather-icon name="edit" class="h-current">Edit</feather-icon>
                        </button>
                        <form action="/papers/{{ $paper->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 hover:text-red-700">
                                <feather-icon name="trash-2" class="h-current">Delete</feather-icon>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>
@endsection