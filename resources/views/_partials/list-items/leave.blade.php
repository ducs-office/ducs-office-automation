<li class="px-4 py-3 border-b last:border-b-0">
    <div class="flex items-center">
        <h5 class="font-bold flex-1">
            {{ $leave->reason }}
            <div class="text-sm text-gray-500 font-bold">
                ({{ $leave->from->format('Y-m-d') }} - {{$leave->to->format('Y-m-d')}})
            </div>
        </h5>
        <a target="_blank" href="{{ route('scholars.leaves.application', [$scholar, $leave]) }}"
            class="btn inline-flex items-center ml-2">
            <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
            Application
        </a>
        @if ($leave->isApproved() || $leave->isRejected())
            <a target="_blank" href="{{ route('scholars.leaves.response_letter', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
                <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                Response
            </a>
        @endif
        <div class="flex items-center px-4">
            <x-feather-icon name="{{ $leave->status->getContextIcon() }}"
                class="h-current {{ $leave->status->getContextCSS() }} mr-2" stroke-width="2.5"></x-feather-icon>
            <div class="capitalize">
                {{ $leave->status }}
            </div>
        </div>
        @can('recommend', [$leave, $scholar])
            <button type="submit" form="patch-form"
                class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                formaction="{{ route('scholars.leaves.recommend', [$scholar, $leave]) }}">
                Recommend
            </button>
        @endcan
        @can('respond', $leave)
        <x-modal.trigger
        :livewire="['payload' => $leave->id]"
        modal="respond-to-leave-modal"
        class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg"
        title="Respond"> 
            Respond
        </x-modal.trigger>
        @endcan
        @can('extend', [$leave, $scholar])
        <x-modal.trigger 
        :livewire="['payload' => ['extensionId' => $leave->id, 'extensionFromDate'=> $leave->nextExtensionFrom()->format('Y-m-d')]]"
        modal="apply-for-leave-modal"  
        title="Extend" 
        class="btn btn-magenta text-sm is-sm ml-4">
            Extend
        </x-modal.trigger>
        @endcan
    </div>
    {{-- inception --}}
    <div class="ml-3 border-l-4">
        @foreach($leave->extensions as $extensionLeave)
        @include('_partials.list-items.leave-extension')
        @endforeach
    </div>
</li>
