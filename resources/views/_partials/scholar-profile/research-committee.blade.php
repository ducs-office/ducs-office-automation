@php
$currentSupervisor = $scholar->currentSupervisor;
$currentCosupervisor = $scholar->currentCosupervisor;
$currentAdvisors = $scholar->currentAdvisors;
@endphp
@push('modals')
@can('manageAdvisoryCommittee', $scholar)
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
@endcan
@endpush
<div class="flex items-center">
    <h3 class="px-3 text-lg font-bold">
        Research Committee
    </h3>
</div>
<div class="flex-1 mt-4 space-y-4">
    <div>
        <div class="px-2 text-sm font-bold flex justify-between mb-1">
            <h4 class="text-gray-800 uppercase tracking-wider">Supervisor</h4>
            <div class="inline-flex items-center space-x-4">
                <button class="inline-flex items-center space-x-1 link">
                    <x-feather-icon name="clock" class="h-current"></x-feather-icon>
                    <span>See History</span>
                </button>
                <h6 class="text-gray-600">Since {{ $currentSupervisor->pivot->started_on->format('M d, Y') }}</h6>
            </div>
        </div>
        <div class="flex items-center space-x-3 border rounded-lg px-4 py-2">
            <img src="{{ $currentSupervisor->avatar_url }}" alt="{{ $currentSupervisor->name }}"
                class="w-10 h-10 rounded-full overflow-hidden bg-gray-400">
            <div>
                <h3 class="font-bold">{{ $currentSupervisor->name }}</h3>
                <p>
                    <a class="link" href="mailto:{{ $currentSupervisor->email }}">{{ $currentSupervisor->email }}</a>
                    @isset($currentSupervisor->phone)<a class="link ml-2" href="tel:{{ $currentSupervisor->phone }}">{{ $currentSupervisor->phone }}</a>@endisset
                </p>
                <div class="italic">{{ $currentSupervisor->designation ?? 'Unknown' }}, {{ $currentSupervisor->affiliation }}</div>
            </div>
        </div>
    </div>
    <div>
        <div class="px-2 text-sm font-bold flex justify-between mb-1">
            <h4 class="text-gray-800 uppercase tracking-wider">Cosupervisor</h4>
            <div class="inline-flex items-center space-x-4">
                <button class="inline-flex items-center space-x-1 link">
                    <x-feather-icon name="clock" class="h-current"></x-feather-icon>
                    <span>See History</span>
                </button>
                <h6 class="text-gray-600">Since {{ $currentSupervisor->pivot->started_on->format('M d, Y') }}</h6>
            </div>
        </div>
        <div class="flex items-center space-x-3 border rounded-lg px-4 py-2">
            @if($currentCosupervisor)
            <img src="{{ $currentCosupervisor->avatar_url }}" alt="{{ $currentCosupervisor->name }}"
                class="w-10 h-10 rounded-full overflow-hidden bg-gray-400">
            <div>
                <h3 class="font-bold">{{ $currentCosupervisor->name }}</h3>
                <p>
                    <a class="link" href="mailto:{{ $currentCosupervisor->email }}">{{ $currentCosupervisor->email }}</a>
                    @isset($currentCosupervisor->phone)<a class="link ml-2"
                        href="tel:{{ $currentCosupervisor->phone }}">{{ $currentCosupervisor->phone }}</a>@endisset
                </p>
                <div class="italic">{{ $currentSupervisor->designation ?? 'Unknown' }},
                    {{ $currentSupervisor->affiliation }}</div>
            </div>
            @else
                <p class="text-gray-600 font-bold">No Cosupervisor</p>
            @endif
        </div>
    </div>
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
                <button class="inline-flex items-center space-x-1 link">
                    <x-feather-icon name="clock" class="h-current"></x-feather-icon>
                    <span>See History</span>
                </button>
                <h6 class="text-gray-600">Since {{ $currentAdvisors->min('pivot.started_on')->format('M d, Y') }}</h6>
                @endif
            </div>
        </div>
        <ul class="border rounded-lg overflow-hidden divide-y mb-4">
            @forelse ($scholar->currentAdvisors as $member)
            <li class="px-4 py-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}"
                    class="w-10 h-10 rounded-full overflow-hidden bg-gray-400">
                    <div>
                        <h3 class="font-bold">{{ $member->name }}</h3>
                        <p>
                            <a class="link" href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                            @isset($member->phone)<a class="link ml-2" href="tel:{{ $member->phone }}">{{ $member->phone }}</a>@endisset
                        </p>
                        <div class="italic">{{ $member->designation ?? 'Unknown' }}, {{ $member->affiliation }}</div>
                    </div>
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
