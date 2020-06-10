{{-- Meetings --}}
@push('modals')
<x-modal name="add-advisory-meetings-modal" class="p-6 min-w-1/2" :open="! $errors->default->isEmpty()">
    <h3 class="text-lg font-bold mb-4">Add Advisory Meetings</h3>
    @include('_partials.forms.add-advisory-meetings')
</x-modal>
@endpush
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Advisory Meetings
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->advisoryMeetings as $meeting)
            <li class="px-4 py-3 border-b last:border-b-0">
                @can('view', [$meeting, $scholar])
                <div class="flex items-center">
                    <h5 class="font-bold flex-1">
                        {{ $meeting->date->format('D M d, Y') }}
                    </h5>
                    <a href="{{ route('scholars.advisory_meetings.show', [$scholar, $meeting]) }}"
                        class="inline-flex items-center underline px-4 py-2 text-gray-900 rounded font-bold">
                        <x-feather-icon name="paperclip" class="h-4 mr-2"></x-feather-icon>
                        Minutes of Meeting
                    </a>
                </div>
                @endcan
            </li>
            @empty
            <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Meetings yet.</li>
            @endforelse
        </ul>
        @can('create', [App\Models\AdvisoryMeeting::class,$scholar])
        <x-modal.trigger class="mt-2 w-full btn btn-magenta rounded-lg py-3" modal="add-advisory-meetings-modal">
            + Add Meetings
        </x-modal.trigger>
        @endcan
    </div>
</div>
