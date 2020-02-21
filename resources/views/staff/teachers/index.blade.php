@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-center px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">College Teacher</h1>
        @can('create', App\Teacher::class)
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-teacher-modal')">
            New
        </button>
        @include('staff.teachers.modals.create', [
            'modalName' => 'create-teacher-modal',
        ])
        @endcan
    </div>

    @can('update', App\Teacher::class)
    @include('staff.teachers.modals.edit', [
        'modalName' => 'edit-teacher-modal',
    ])
    @endcan
    @forelse($teachers as $teacher)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
            <div class="px-2 w-64">
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($teacher->name) }}
                </h3>
                <h4 class="text-sm font-semibold text-gray-600 mr-2">{{ $teacher->email }}</h4>
            </div>
            <div class="ml-auto px-2 flex items-center">
                <a href="{{ route('staff.teachers.show', $teacher) }}"
                    class="p-1 hover:text-blue-700 mr-2">
                    <feather-icon class="h-4" name="eye" stroke-width="2.5">View</feather-icon>
                </a>
                @can('update', App\Teacher::class)
                <button type="submit" class="p-1 hover:text-red-700 mr-2"
                    @click="
                        $modal.show('edit-teacher-modal', {
                            Teacher: {
                                id: {{ $teacher->id }},
                                first_name: {{ json_encode($teacher->first_name) }},
                                last_name: {{ json_encode($teacher->last_name) }},
                                email: {{ json_encode($teacher->email) }},
                            },
                        })">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', $teacher)
                <form action="{{ route('staff.teachers.destroy', $teacher) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete teacher \'{{ $teacher->name }}\'?');">
                    @csrf_token @method('delete')
                    <button type="submit" class="p-1 hover:text-red-700">
                        <feather-icon class="h-current" name="trash-2">Trash</feather-icon>
                    </button>
                </form>
                @endcan
            </div>
        </div>
    @empty
        <div class="py-8 flex flex-col items-center justify-center text-gray-500">
            <feather-icon name="frown" class="h-16"></feather-icon>
            <p class="mt-4 mb-2  font-bold">
                Sorry! No College Teachers {{ count(request()->query()) ? 'found for your query.' : 'added yet.' }}
            </p>
        </div>
    @endforelse
</div>
@endsection