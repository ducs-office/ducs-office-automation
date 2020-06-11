@push('modals')
<livewire:apply-for-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
<livewire:respond-to-leave-modal :error-bag="$errors->default" :scholar="$scholar"/>
@endpush

<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Leaves
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        @can('create', [Leave::class, $scholar])
        <div class="mt-3 text-right">
            <x-modal.trigger :livewire="['payload' => '']" modal="apply-for-leave-modal"  title="Apply" class="btn btn-magenta is-sm">
                Apply For Leaves
            </x-modal.trigger>
        </div>
        @endcan
    </div>
    <div class="flex-1">
        <form id="patch-form" method="POST" class="w-0">
            @csrf_token @method("PATCH")
        </form>
        <ul class="w-full border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->leaves as $leave)
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
                    @endforeach
                </div>
            </li>
            @empty
            <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Leaves</li>
            @endforelse
        </ul>
    </div>
</div>
