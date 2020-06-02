@extends('layouts.master')
@push('modals')
<x-modal name="create-scholar-modal" class="min-w-1/3 p-6">
    <h2 class="text-lg font-bold mb-8">Create Scholar</h2>
    @include('_partials.forms.create-scholar')
</x-modal>
@can('update', App\Models\Scholar::class)
    <livewire:edit-scholar-modal :supervisors="$supervisors" :cosupervisors="$cosupervisors"/>
@endcan
<livewire:replace-supervisor-modal :supervisors="$supervisors"/>
<livewire:replace-cosupervisor-modal :cosupervisors="$cosupervisors" />
@endpush
@section('body')
<div class="page-card p-0">
    <div class="flex items-center p-6 border-b">
        <h1 class="page-header mb-0 px-0 mr-4">Scholars</h1>
        @can('create', App\Models\Scholar::class)
        <x-modal.trigger class="btn btn-magenta is-sm shadow-inner"
            modal="create-scholar-modal">
            New
        </x-modal.trigger>
        @endcan
    </div>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">Scholar</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">Area of Research</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">Supervisor</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">Cosupervisor</th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($scholars as $scholar)
                    @include('_partials.list-items.scholar')
                @empty
                    <tr>
                        <td colspan="5" class="py-8 flex flex-col items-center justify-center text-gray-500">
                            <x-feather-icon name="frown" class="h-16"></x-feather-icon>
                            <p class="mt-4 mb-2 font-bold">
                                Sorry! No Scholars added yet.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
