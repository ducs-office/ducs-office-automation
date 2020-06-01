{{-- Meetings --}}
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
                <div class="flex items-center">
                    <h5 class="font-bold flex-1">
                        {{ $meeting->date->format('D M d, Y') }}
                    </h5>
                    <a href="{{ route('research.scholars.advisory_meetings.minutes_of_meeting', $meeting) }}"
                        class="inline-flex items-center underline px-4 py-2 text-gray-900 rounded font-bold">
                        <x-feather-icon name="paperclip" class="h-4 mr-2"></x-feather-icon>
                        Minutes of Meeting
                    </a>
                </div>
            </li>
            @empty
            <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Meetings yet.</li>
            @endforelse
        </ul>
        @can('scholars.advisory_meetings.store', $scholar)
        <button class="mt-2 w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-advisory-meetings-modal')">
            + Add Meetings
        </button>
        <v-modal name="add-advisory-meetings-modal" height="auto">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Add Advisory Meetings</h3>
                <form action="{{ route('research.scholars.advisory_meetings.store', $scholar) }}" method="POST"
                    class="flex" enctype="multipart/form-data">
                    @csrf_token
                    <input id="date" name="date" type="date" class="form-input rounded-r-none">
                    <input type="file" name="minutes_of_meeting" id="minutes_of_meeting" class="w-full flex-1 form-input rounded-none" accept="document/*">
                    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                </form>
            </div>
        </v-modal>
        @endcan
    </div>
</div>
