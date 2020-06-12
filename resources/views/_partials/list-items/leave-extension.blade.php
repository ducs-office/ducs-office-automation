<div class="flex items-center ml-6 mt-4">
    <h5 class="font-bold flex-1">
        {{ $extensionLeave->reason }}
        <div class="text-sm text-gray-500 font-bold">
            (extension till {{$extensionLeave->to->format('Y-m-d')}})
        </div>
    </h5>
    <a target="_blank" href="{{ route('scholars.leaves.application', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
        <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
        Application
    </a>
    @if ($extensionLeave->isApproved() || $extensionLeave->isRejected())
        <a target="_blank" href="{{ route('scholars.leaves.response_letter', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
            <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
            Response
        </a>
    @endif
    <div class="flex items-center px-4">
        <x-feather-icon name="{{ $extensionLeave->status->getContextIcon() }}"
            class="h-current {{ $extensionLeave->status->getContextCSS() }} mr-2" stroke-width="2.5">
        </x-feather-icon>
        <div class="capitalize">
            {{ $extensionLeave->status }}
        </div>
    </div>
    @can('recommend', [$extensionLeave, $scholar])
    <button type="submit" form="patch-form"
        class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
        formaction="{{ route('scholars.leaves.recommend', [$scholar, $extensionLeave]) }}">
        Recommend
    </button>
    @endcan
    @can('respond', $extensionLeave)
    <x-modal.trigger
    :livewire="['payload' => $extensionLeave->id]"
    modal="respond-to-leave-modal"
    class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg"
    title="Respond"> 
        Respond
    </x-modal.trigger>
    @endcan
</div>