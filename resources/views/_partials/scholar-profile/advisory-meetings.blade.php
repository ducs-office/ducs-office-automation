@push('modals')
<x-modal name="add-advisory-meetings-modal" class="p-6 min-w-1/2" :open="! $errors->advisoryMeeting->isEmpty()">
    <h3 class="text-lg font-bold mb-4">Add Advisory Meetings</h3>
    @include('_partials.forms.add-advisory-meetings')
</x-modal>
@endpush
<div class="page-card p-6 overflow-visible">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="briefcase" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Advisory Committee Meetings</h2>
        </div>
        @can('create', [App\Models\AdvisoryMeeting::class,$scholar])
            <x-modal.trigger class="ml-auto inline-flex items-center space-x-1 btn btn-magenta is-sm"
                modal="add-advisory-meetings-modal">
                <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
                <span>Add Meetings</span>
            </x-modal.trigger>
        @endcan
    </div>
    <ul class="border rounded-lg overflow-hidden mb-4">
        @forelse ($scholar->advisoryMeetings as $meeting)
        @include('_partials.list-items.advisory-meeting')
        @empty
        <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Meetings yet.</li>
        @endforelse
    </ul>
</div>
