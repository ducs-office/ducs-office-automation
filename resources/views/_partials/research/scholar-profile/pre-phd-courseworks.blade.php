@push('modals')
    <x-modal name="add-scholar-coursework-modal" class="p-6 w-1/2" 
        :open="!$errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Add Coursework - {{ $scholar->name }}</h2>
            @include('_partials.forms.add-scholar-coursework' ,[
                'courses' => $courses,
            ])
    </x-modal>
    <livewire:mark-scholar-coursework-completed-modal :error-bag="$errors->update" :scholar="$scholar" />
@endpush
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Pre-PhD Coursework
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @foreach ($scholar->courseworks as $course)
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
            @endforeach
        </ul>
        @can('create', [App\Models\Pivot\ScholarCoursework::class, $scholar])
            <x-modal.trigger  modal="add-scholar-coursework-modal"
                class="w-full btn btn-magenta rounded-lg py-3">
                + Add Coursework
            </x-modal.trigger>
        @endcan
    </div>
</div>
