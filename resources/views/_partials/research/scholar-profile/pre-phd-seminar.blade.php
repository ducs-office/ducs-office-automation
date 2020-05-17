<div class="page-card p-6 overflow-visible space-x-6" x-data="{isOpen: false, ...modalsRoot() }">
    <div class="flex items-baseline w-full">
        <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Pre PhD Seminar
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
            <div clas="mt-3 text-right">
                @can('requestPhDSeminar', \App\Models\ScholarAppeal::class)
                    @if ($scholar->isDocumentListComplete() && $scholar->publications->count() && $scholar->proposed_title)
                        <a href="{{ route('scholars.pre_phd_seminar.show', $scholar) }}"
                            class="btn btn-magenta">
                                Request
                        </a>
                    @else    
                        <button class="btn btn-magenta" x-on:click="$modal.show('seminar-requirements-modal')"> 
                            Request
                        </button>
                    @endif
                @endcan
            </div>
        </div>
        <div class="ml-auto flex">
            @if (optional($scholar->currentPhdSeminarAppeal())->isCompleted())
                <h3 class="font-bold">Finalized Title: </h3>
                <h3 class="text-magenta-500 ml-2"> {{$scholar->finalized_title}}</h3>
            @else
                <div class="flex items-baseline">
                    <h3 class="font-bold">Proposed Title: </h3>
                    <h3 class="text-magenta-500 ml-2"> {{$scholar->proposed_title ?? 'not set'}}</h3>
                    @can('requestPhDSeminar', \App\Models\ScholarAppeal::class)
                    <button class="btn btn-magenta ml-4 rounded-sm text-sm" x-on:click="$modal.show('update-proposed-title-modal')">
                        Edit
                    </button>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <x-modal name="seminar-requirements-modal" class="p-6">
        <div> 
            <p class="text-lg mb-3 font-bold">Your profile needs to have the following before applying for Pre-PhD Seminar</p>
            <ul class="list-disc px-6">
                <li class="font-bold m-2 
                    {{ $scholar->proposed_title ? 'text-green-700' : 'text-gray-700 '}}"> 
                    Proposed Title for the Seminar 
                </li>
                <li class="font-bold m-2 
                    {{$scholar->isJoiningLetterUploaded() ? 'text-green-700' : 'text-gray-700 '}}"> 
                    Joining Letter 
                </li>
                <li class="font-bold m-2
                    {{$scholar->isAcceptanceLetterUploaded() ? 'text-green-700': 'text-gray-700'}}">
                    Reprints/Preprints/Acceptance letter 
                </li>
                <li class="font-bold m-2
                    {{$scholar->publications->count() ? 'text-green-700': 'text-gray-700'}}"> 
                    At least 1 Publication
                </li>
                <li class="font-bold m-2 text-gray-700"> 
                    Extension Letter from BRS (if any)
                </li>
            </ul>
        </div>
    </x-modal>

    <x-modal name="update-proposed-title-modal" class="p-6">
        <h2 class="text-lg font-bold mb-6">Update Proposed Title</h2>
        <form action="{{ route('scholars.proposed_title.update', $scholar)}}" method="post">
            @csrf_token
            @method("PATCH")
            <div class="flex items-center">
                <label for="proposed_title" class="form-label">
                    Proposed Title
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" name="proposed_title" class="form-input ml-2">
                <button type="submit" class="btn btn-magenta ml-2">Update</button>
            </div>
        </form>
    </x-modal>
    
    <form id="patch-form" method="POST" class="w-0">
        @csrf_token @method("PATCH")
    </form>
    @foreach ($scholar->phdSeminarAppeals() as $phdSeminarAppeal)
    <div class="mt-4 flex w-full items-center justify-between">
        <p class="font-bold pl-2 m-1"> Applied On: <span class="font-medium"> {{ $phdSeminarAppeal->applied_on }}</span></p>
        @if ($phdSeminarAppeal->isRejected())
            <p class="font-bold pl-2 m-1"> Title: <span class="font-medium"> {{ $phdSeminarAppeal->proposed_title }}</span></p>
        @else
            @can('viewPhdSeminarForm', [\App\Models\ScholarAppeal::class, $scholar])
                <a href="{{ route('scholars.pre_phd_seminar.show', $scholar) }}" target="_blank" class="inline-flex items-center underline px-3 py-1 bg-magenta-100 text-magenta-800 rounded font-bold mx-2">
                    <x-feather-icon name="link" class="h-4 mr-2"> Pre-Phd Seminar Form </x-feather-icon>
                    Pre-PhD Seminar Form
                </a>
            @endcan
        @endif
        <div class="mx-2">
            <div class="flex justify-center items-center px-4 mr-2">
                <x-feather-icon name="{{ $phdSeminarAppeal->status->getContextIcon() }}"
                    class="h-current {{ $phdSeminarAppeal->status->getContextCSS() }} mr-2" 
                    stroke-width="2.5">
                </x-feather-icon>
                <div class="capitalize mr-2">
                    {{ $phdSeminarAppeal->status }}
                </div>
                @can('respond', $phdSeminarAppeal)
                    <button type="submit" form="patch-form"
                        class="px-4 py-2 mr-1 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                        formaction="{{ route('scholars.appeals.approve', [$scholar, $phdSeminarAppeal]) }}">
                        Approve
                    </button>
                    <button type="submit" form="patch-form"
                        class="px-4 py-2 mr-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded font-bold"
                        formaction="{{ route('scholars.appeals.reject', [$scholar, $phdSeminarAppeal]) }}">
                        Reject
                    </button>
                @endcan
                @can('markComplete', $phdSeminarAppeal)
                    <button class="btn  btn-magenta mt-4" x-on:click="$modal.show('mark-seminar-appeal-complete-modal')"> Conclude </button>
                @endcan
            </div>
        </div>
        <x-modal name="mark-seminar-appeal-complete-modal" class="p-6">
            <h2 class="text-lg font-bold mb-6">Conclude Seminar Appeal</h2>
            <form action="{{ route('scholars.appeals.mark_complete',[ $scholar, $phdSeminarAppeal])}}" method="post">
                @csrf_token
                @method("PATCH")
                <div class="flex items-center mb-2">
                    <label for="finalized_title" class="form-label">
                        Finalized Title
                        <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="finalized_title" class="form-input ml-2">
                </div>
                <div class="flex items-center mb-2">
                    <label for="title_finalized_on" class="form-label">
                        Date of Seminar Meeting
                        <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="title_finalized_on" class="form-input ml-2">
                </div>
                <button type="submit" class="btn btn-magenta ml-2">Conclude</button>
            </form>
        </x-modal>
    </div>
    @endforeach
</div>