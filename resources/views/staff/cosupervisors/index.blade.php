@extends('layouts.master')
@section('body')
<div class="page-card m-6">
    <div class="flex items-baseline px-6 pb-4 border-b justify-between">
        <h1 class="page-header mb-0 px-0 mr-4">Co-supervisors</h1>
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('create-cosupervisor-modal')">
            Create New Co-supervisor
        </button>
        @include('staff.cosupervisors.modals.create', [
            'modalName' => 'create-cosupervisor-modal',
        ])
        <button class="btn btn-magenta is-sm shadow-inner"
            @click.prevent="$modal.show('add-college-teacher-modal')">
            Add From Existing Teachers
        </button>
        @include('staff.cosupervisors.modals.add-existing-teacher', [
            'modalName' => 'add-existing-teacher-modal',
            'teachers' => $users,
        ])
    </div>

    @include('staff.cosupervisors.modals.edit', [
        'modalName' => 'update-cosupervisor-modal',
    ])

    @foreach($cosupervisors as $cosupervisor)
        <div class="px-4 py-2 hover:bg-gray-100 border-b flex justify-between">
            <div class="px-2">
                <h2 class="text-lg font-bold flex items-center">
                    {{ ucwords($cosupervisor->name) }}
                </h2>
                <h4 class="text-sm font-semibold text-gray-600 mb-1">{{ $cosupervisor->email }}</h4>
                <h4 class="text-sm font-semibold text-gray-600"> {{ $cosupervisor->designation }} <strong>at</strong> {{ $cosupervisor->affiliation }} </h4>
            </div>
            @if($cosupervisor->professor === null)
                <div class="flex ml-auto items-center">
                    <button type="submit" class="p-1 hover:text-blue-700 mr-2"
                        @click="
                            $modal.show('update-cosupervisor-modal', {
                                cosupervisor: {
                                    id: {{ $cosupervisor->id }},
                                    name: {{ json_encode($cosupervisor->name) }},
                                    email: {{ json_encode($cosupervisor->email) }},
                                    designation: {{ json_encode($cosupervisor->designation) }},
                                    affiliation: {{ json_encode($cosupervisor->affiliation) }},
                                },
                            })">
                        <x-feather-icon class="h-current" name="edit">Edit</x-feather-icon>
                    </button>
                    <form action="{{ route('staff.cosupervisors.destroy', $cosupervisor) }}" method="POST">
                        @csrf_token @method('delete')
                        <button type="submit" class="p-1 hover:text-red-700">
                            <x-feather-icon class="h-current" name="trash-2">Trash</x-feather-icon>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
