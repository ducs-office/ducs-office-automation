@php
$currentCosupervisor = $scholar->currentCosupervisor;
@endphp
@push('modals')
    <x-modal name="cosupervisors-history-modal" class="p-6 w-1/2 mx-auto">
        <div class="border-b -mx-6 px-6 -mt-6 pt-4 pb-3 mb-3">
            <h4 class="text-xl font-bold flex items-center">
                <x-feather-icon name="git-commit" class="h-current mr-4"></x-feather-icon>
                <span>Co-Supervisors' History</span>
            </h4>
        </div>
        @forelse ($cosupervisors as $cosupervisor)
        <x-timeline-item icon="circle" color="text-gray-400">
            <h4 class="font-bold mb-3">{{ $cosupervisor->pivot->started_on->format('M d, Y') }} - {{ optional($cosupervisor->pivot->ended_on)->format('M d, Y') }}</h4>
            <ul class="border rounded divide-y">
                <li class="px-4 py-2 flex items-center space-x-3 space-y-3">
                    @include('_partials.research-committee-member', [
                        'member' => $cosupervisor
                    ])
                </li>
            </ul>
        </x-timeline-item>
        @empty
            <div class="mt-6 text-gray-600 font-bold"> Nothing to see here. </div>
        @endforelse
    </x-modal>
@endpush
<div class="flex-1 mt-4 space-y-4">
    <div>
        <div class="px-2 text-sm font-bold flex justify-between mb-1">
            <h4 class="text-gray-800 uppercase tracking-wider">Cosupervisor</h4>
            <div class="inline-flex items-center space-x-4">
                <x-modal.trigger modal="cosupervisors-history-modal" class="inline-flex items-center space-x-1 link">
                    <x-feather-icon name="clock" class="h-current"></x-feather-icon>
                    <span>See History</span>
                </x-modal.trigger>
                @if($currentCosupervisor)
                    <h6 class="text-gray-600">Since {{ $currentCosupervisor->pivot->started_on->format('M d, Y') }}</h6>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-3 border rounded-lg px-4 py-2">
            @if($currentCosupervisor)
                @include('_partials.research-committee-member', [
                    'member' => $currentCosupervisor
                ])
            @else
                <p class="text-gray-600 font-bold">No Cosupervisor</p>
            @endif
        </div>
    </div>
</div>
