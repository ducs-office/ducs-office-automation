@extends('layouts.master')
@push('modals')
    <x-modal name="create-cosupervisor-modal" class="w-1/3 p-6" :open="$errors->any()">
        <h2 class="mb-8 font-bold text-lg">Create New Co-supervisor</h2>
        @include('_partials.forms.create-cosupervisor')
    </x-modal>
@endpush
@section('body')
<div class="page-card p-0">
    <div class="flex items-baseline px-6 py-6 border-b justify-between">
        <h1 class="page-header mb-0 px-0 mr-4">Co-supervisors</h1>
        <x-modal.trigger class="btn btn-magenta is-sm shadow-inner" modal='create-cosupervisor-modal'>
            Add from Existing Teachers
        </x-modal.trigger>
    </div>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider">Designation</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-bold text-gray-600 uppercase tracking-wider"></th>
               </tr>
            </thead>
            <tbody>
                @forelse($cosupervisors as $cosupervisor)
                    @include('_partials.list-items.cosupervisor')
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-gray-500">
                            <x-feather-icon name="frown" class="w-16 mx-auto"></x-feather-icon>
                            <p class="mt-4 text-center font-bold">
                                Sorry! No cosupervisors {{ count(request()->query()) ? 'found for your query.' : 'have been created yet.' }}
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
