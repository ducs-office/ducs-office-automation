@php
$currentAdvisors = $scholar->currentAdvisors;
@endphp
@push('modals')
    <x-modal name="update-advisors-modal" class="p-6 w-1/2 mx-auto"
        :open="$errors->update->hasAny(['advisors', 'advisors.*'])">
        @include('_partials.forms.manage-advisors', [
            'headerMessage' => 'Update Advisors',
            'routeName' => 'scholars.advisors.update',
        ])
    </x-modal>
    <x-modal name="replace-advisors-modal" class="p-6 w-1/2 mx-auto" 
        :open="$errors->update->hasAny(['advisors', 'advisors.*'])">
        @include('_partials.forms.manage-advisors', [
            'headerMessage' => 'Replace Advisors',
            'routeName' => 'scholars.advisors.replace'
        ])
    </x-modal>
    <x-modal name="advisors-history-modal" class="p-6 w-1/2 mx-auto">
        <div class="border-b -mx-6 px-6 -mt-6 pt-4 pb-3 mb-3">
            <h4 class="text-xl font-bold flex items-center">
                <x-feather-icon name="git-commit" class="h-current mr-4"></x-feather-icon>
                <span>Advisors' History</span>
            </h4>
        </div>
        @forelse ($advisors as $advisorGroup)
        <x-timeline-item icon="circle" color="text-gray-400">
            <h4 class="font-bold mb-3">{{ $advisorGroup[0]->pivot->started_on->format('M d, Y') }} - {{ optional($advisorGroup[0]->pivot->ended_on)->format('M d, Y') }}</h4>
            <ul class="border rounded divide-y flex flex-wrap divide-x">
                @foreach($advisorGroup as $advisor)
                    <li class="px-4 py-2 flex flex-1 items-center space-x-3 space-y-3">
                        @include('_partials.research-committee-member', [
                            'member' => $advisor
                        ])
                    </li>
                @endforeach
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
            <h4 class="text-gray-800 uppercase tracking-wider">Advisors</h4>
            <div class="inline-flex items-center space-x-4">
                @can('manageAdvisoryCommittee', $scholar)
                    <x-modal.trigger modal="update-advisors-modal" class="inline-flex items-center space-x-1 link">
                        <x-feather-icon name="edit-3" class="h-current"></x-feather-icon>
                        <span>Change</span>
                    </x-modal.trigger>
                    <x-modal.trigger modal="replace-advisors-modal" class="inline-flex items-center space-x-1 link">
                        <x-feather-icon name="refresh-cw" class="h-current"></x-feather-icon>
                        <span>Replace</span>
                    </x-modal.trigger>
                @endcan
                @if($currentAdvisors->count() > 0)
                <x-modal.trigger modal="advisors-history-modal" class="inline-flex items-center space-x-1 link">
                    <x-feather-icon name="clock" class="h-current"></x-feather-icon>
                    <span>See History</span>
                </x-modal.trigger>
                <h6 class="text-gray-600">Since {{ $currentAdvisors->min('pivot.started_on')->format('M d, Y') }}</h6>
                @endif
            </div>
        </div>
        <ul class="border rounded-lg overflow-hidden divide-y mb-4">
            @forelse ($scholar->currentAdvisors as $advisor)
            <li class="px-4 py-3">
                <div class="flex items-center space-x-3">
                    @include('_partials.research-committee-member', [
                        'member' => $advisor
                    ])
                </div>
            </li>
            @empty
            <li class="px-4 py-2">
                <p class="text-gray-600 font-bold">
                    No Advisors assigned.
                </p>
            </li>
            @endforelse
        </ul>
    </div>
</div>
