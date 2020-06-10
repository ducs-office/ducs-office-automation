@push('modals')    
<x-modal name="edit-proposed-title-modal" class="p-6" :open="$errors->default->hasAny(['proposed_title'])">
    <h2 class="text-lg font-bold mb-6">Update Proposed Title</h2>
    @include('_partials.forms.edit-proposed-title')
</x-modal>
@if ($scholar->prePhdSeminar)
    @can('addSchedule', [$scholar->prePhdSeminar, $scholar])
    <x-modal name="schedule-seminar-appeal-modal" class="p-6" :open="$errors->default->hasAny('scheduled_on')">
        <h2 class="text-lg font-bold mb-6">Add Schedule Of Seminar Appeal</h2>
        @include('_partials.forms.schedule-seminar-appeal')
    </x-modal>
    @endcan
    @can('finalize', [$scholar->prePhdSeminar, $scholar])
    <x-modal name="finalize-seminar-appeal-modal" class="p-6" :open="$errors->default->hasAny('finalized_title')">
        <h2 class="text-lg font-bold mb-6">Finalize Seminar Appeal</h2>
        @include('_partials.forms.finalize-seminar-appeal')
    </x-modal>
    @endcan
@endif
<x-modal name="seminar-requirements-modal" class="p-6" :open="false">
    <div>
        <p class="text-lg mb-3 font-bold">Your profile needs to have the following before applying for Pre-PhD Seminar</p>
        <ul class="list-disc px-6">
            <li class="font-bold m-2 {{$scholar->isJoiningLetterUploaded() ? 'text-green-700' : 'text-gray-700 '}}">
                Joining Letter
            </li>
            <li class="font-bold m-2 {{$scholar->journals->count() ? 'text-green-700': 'text-gray-700'}}">
                At least 1 Journal Publication
            </li>
            <li class="font-bold m-2 text-gray-700">
                Extension Letter from BRS (if any)
            </li>
            <li class="font-bold m-2 {{ $scholar->proposed_title ? 'text-green-700' : 'text-gray-700 '}}">
                Proposed Title for the Seminar
            </li>
            <li class="font-bold m-2 {{ $scholar->areCourseworksCompleted() ? 'text-green-700' : 'text-gray-700 '}}">
                All Courseworks should be completed
            </li>
        </ul>
    </div>
</x-modal>
@endpush

<div class="page-card p-6 overflow-visible flex space-x-6">
    <div>
        <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Pre PhD Seminar
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        <div clas="mt-3 text-right">
        @can('create', [\App\Models\PrePhdSeminar::class, $scholar])
            <a href="{{ route('scholars.pre_phd_seminar.request', $scholar) }}" class="btn btn-magenta">
                Request
            </a>
        @elsecan('request', [\App\Models\PrePhdSeminar::class, $scholar])
            <x-modal.trigger class="btn btn-magenta" modal="seminar-requirements-modal">
                Request
            </x-modal.trigger>
        @endcan
        </div>
    </div>
    <div class="flex-1">
        @if (optional($scholar->prePhdSeminar)->isCompleted())
        <div class="ml-auto flex items-baseline justify-end w-full">
            <h3 class="font-bold">Finalized Title: </h3>
            <h3 class="text-gray-800 mx-2"> {{$scholar->prePhdSeminar->finalized_title}}</h3>
        </div>
        @else
        <div class="flex items-baseline justify-end w-full">
            <h3 class="font-bold">Proposed Title: </h3>
            <h3 class="text-gray-800 mx-2"> {{$scholar->proposed_title ?? 'not set'}}</h3>
            @can('request', [\App\Models\PrePhdSeminar::class, $scholar])
            <x-modal.trigger class="btn btn-magenta ml-4 py-1 px-2 rounded-sm text-sm" modal="edit-proposed-title-modal">
                Edit
            </x-modal.trigger>
            @endcan
        </div>
        @endif
        <div class="w-full border rounded-lg mb-4 m-2 flex items-center">
            @if ($scholar->prePhdSeminar)
            @if ($scholar->prePhdSeminar->scheduled_on)
            <p class="font-bold mr-2 p-2 my-2">
               Scheduled On: <span class="mx-1 text-gray-600 font-noraml"> {{ $scholar->prePhdSeminar->scheduled_on }} </span>
            </p>
            @else
            <p class="font-bold mr-2 p-2 my-2">
                Applied On: <span class="mx-1 text-gray-600 font-noraml"> {{ $scholar->prePhdSeminar->applied_on }} </span>
            </p>
            @endif
            @can('view', [$scholar->prePhdSeminar, $scholar])
            <div class="ml-4">
                <a href="{{ route('scholars.pre_phd_seminar.show', [$scholar, $scholar->prePhdSeminar]) }}"
                    target="_blank" class="inline-flex items-center underline px-3 py-1 bg-magenta-100 text-magenta-800 rounded font-bold">
                    <x-feather-icon name="link" class="h-4 mr-2"> Pre-Phd Seminar Form </x-feather-icon>
                    Pre-PhD Seminar Form
                </a>
            </div>
            @endcan
            <div class="flex ml-auto items-baseline">
                <p class="px-3 mr-2 py-1 text-center flex items-center font-lg font-bold border border-4 border-solid rounded-full
                    {{ $scholar->prePhdSeminar->status->getContextCSS() }}">
                    {{ $scholar->prePhdSeminar->status }}
                </p>
                @can('forward', [$scholar->prePhdSeminar, $scholar])
                <div class="ml-2">
                    <button type="submit" form="patch-form"
                        class="px-4 py-2 mr-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                        formaction="{{ route('scholars.pre_phd_seminar.forward', [$scholar, $scholar->prePhdSeminar]) }}">
                        Forward
                    </button>
                </div>
                @endcan
                @can('addSchedule', [$scholar->prePhdSeminar, $scholar])
                <div class="ml-2">
                    <x-modal.trigger class="px-4 py-2 mr-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                    modal="schedule-seminar-appeal-modal">
                        Schedule
                    </x-modal.trigger>
                </div>
                @endcan
                @can('finalize', [$scholar->prePhdSeminar, $scholar])
                <div class="ml-2">
                    <x-modal.trigger class="px-4 py-2 mr-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold"
                    modal="finalize-seminar-appeal-modal">
                        Finalize
                    </x-modal.trigger>
                </div>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
<form id="patch-form" method="POST" class="w-0">
    @csrf_token @method("PATCH")
</form>
