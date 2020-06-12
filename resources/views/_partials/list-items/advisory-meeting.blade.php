<li class="px-4 py-3 border-b last:border-b-0">
    @can('view', [$meeting, $scholar])
    <div class="flex items-center">
        <h5 class="font-bold flex-1">
            {{ $meeting->date->format('D M d, Y') }}
        </h5>
        <a href="{{ route('scholars.advisory-meetings.show', [$scholar, $meeting]) }}"
            class="inline-flex items-center underline px-4 py-2 text-gray-900 rounded font-bold">
            <x-feather-icon name="paperclip" class="h-4 mr-2"></x-feather-icon>
            Minutes of Meeting
        </a>
    </div>
    @endcan
</li>