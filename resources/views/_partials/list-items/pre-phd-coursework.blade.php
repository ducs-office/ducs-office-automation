@can('view', [$course->pivot, $scholar])
<li class="px-4 py-3 border-b last:border-b-0">
    <div class="flex items-center">
        <div class="w-24">
            <span class="px-3 py-1 text-sm font-bold bg-magenta-200 text-magenta-800 rounded-full mr-4">{{ $course->type }}</span>
        </div>
        <h5 class="font-bold flex-1">
            {{ $course->name }}
            <span class="text-sm text-gray-500 font-bold"> ({{ $course->code }}) </span>
        </h5>
        @if ($course->pivot->completed_on)
            <div class="flex items-center pl-4">
                @can('view', [$course->pivot, $scholar])
                    <a target="_blank"
                    href="{{ route('scholars.courseworks.marksheet', [ $scholar, $course->pivot])}}"
                    class="btn inline-flex items-center ml-2">
                    <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                        Marksheet
                    </a>
                @endcan
                <div class="w-5 h-5 inline-flex items-center justify-center bg-green-500 text-white font-extrabold leading-none rounded-full mr-2">&checkmark;</div>
                <div>
                    Completed on {{ $course->pivot->completed_on->format('d M, Y') }}
                </div>
            </div>
        @elsecan('markCompleted', $course->pivot)
        <x-modal.trigger :livewire="['payload' => $course->id]" modal="mark-scholar-coursework-completed-modal" title="Edit"
            class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg">
            <x-feather-icon class="h-5" name="check-square">Mark Completed</x-feather-icon>
        </x-modal.trigger>
        @endif
    </div>
</li>
@endcan