@push('modals')
@can('create', \App\Models\ProgressReport::class)
    <x-modal name="add-scholar-progress-reports-modal" class="p-6 w-1/2"
        :open="!$errors->progressReport->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Add Progress Report - {{ $scholar->name }}</h2>
        @include('_partials.forms.add-scholar-progress-reports', [
            'recommendations' => $recommendations,
        ])
    </x-modal>
@endcan
@endpush
<div class="page-card p-6 overflow-visible">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="trending-up" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Progress Reports</h2>
        </div>
        @can('create', \App\Models\ProgressReport::class)
            <x-modal.trigger class="ml-auto inline-flex items-center space-x-1 btn btn-magenta py-1 px-2"
                modal="add-scholar-progress-reports-modal">
                <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
                <span>Add</span>
            </x-modal.trigger>
        @endcan
    </div>
    <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
        @forelse ($scholar->progressReports as $progressReport)
            @include('_partials.list-items.progress-report')
        @empty
            <li class="px-4 py-3 text-center text-gray-700 font-bold">No Progress Reports yet.</li>
        @endforelse
    </ul>
</div>
