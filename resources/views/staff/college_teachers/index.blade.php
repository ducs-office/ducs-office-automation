@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-baseline px-6 pb-4 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">College Teacher</h1>
        @can('create', App\CollegeTeacher::class)
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-college-teacher-modal')">
            New
        </button>
        @include('staff.college_teachers.modals.create', [
            'modalName' => 'create-college-teacher-modal',
        ])
        @endcan
    </div>
    @can('update', App\CollegeTeacher::class)
    @include('staff.college_teachers.modals.edit', [
        'modalName' => 'edit-college-teacher-modal',
    ])
    @endcan
    @forelse($collegeTeachers as $collegeTeacher)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex">
            <div class="px-2 w-64">
                <h3 class="text-lg font-bold mr-2">
                    {{ ucwords($collegeTeacher->getNameAttribute()) }}
                </h3>
                <h4 class="text-sm font-semibold text-gray-600 mr-2">{{ $collegeTeacher->email }}</h4>
            </div>
            <div class="ml-auto px-2 flex items-center">
                @can('update', App\CollegeTeacher::class)
                <button type="submit" class="p-1 hover:text-red-700 mr-2"
                    @click="
                        $modal.show('edit-college-teacher-modal', {
                            collegeTeacher: {
                                id: {{ $collegeTeacher->id }},
                                first_name: {{ json_encode($collegeTeacher->first_name) }},
                                last_name: {{ json_encode($collegeTeacher->last_name) }},
                                email: {{ json_encode($collegeTeacher->email) }},
                            },
                        })">
                    <feather-icon class="h-current" name="edit">Edit</feather-icon>
                </button>
                @endcan
                @can('delete', $collegeTeacher)
                <form action="{{ route('college_teachers.destroy', $collegeTeacher) }}" method="POST"
                    onsubmit="return confirm('Do you really want to delete college teacher \'{{ $collegeTeacher->getNameAttribute() }}\'?');">
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
