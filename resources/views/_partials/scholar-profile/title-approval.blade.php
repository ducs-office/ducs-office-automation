@push('modals')
@can('request',[ \App\Models\TitleApproval::class, $scholar])
    <x-modal name="title-approval-requirements-modal" class="p-6">
        <div>
            <p class="text-lg mb-3 font-bold">Your profile needs to have the following before applying for Title Approval</p>
            <ul class="list-disc px-6">
                <li class="font-bold m-2
                    {{$scholar->isJoiningLetterUploaded() ? 'text-green-700' : 'text-gray-700 '}}">
                    Joining Letter
                </li>
                <li class="font-bold m-2 text-gray-700">
                    Letter of extension from BRS (if any)
                </li>
                <li class="font-bold m-2
                    {{$scholar->isPrePhdSeminarNoticeUploaded() ? 'text-green-700': 'text-gray-700'}}">
                    Copy of the Pre-PhD Seminar notice
                </li>
                <li class="font-bold m-2
                    {{$scholar->isTableOfContentsOfThesisUploaded() ? 'text-green-700': 'text-gray-700'}}">
                    (Proposed)Table of Contents of the Thesis
                </li>
            </ul>
        </div>
    </x-modal>
@endcan

@if($scholar->titleApproval)
	@can('approve', [$scholar->titleApproval, $scholar])
		<x-modal name="approve-scholar-title-modal" class="p-6 w-1/2"
			:open="!$errors->approveTitle->isEmpty()">
			<h2 class="text-lg font-bold mb-8"> Approve Title - {{ $scholar->name }}</h2>
			@include('_partials.forms.approve-scholar-title')
		</x-modal>
	@endcan
@endif

@endpush
<div class="page-card p-6">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="check-circle" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Title Approval (BRS)</h2>
        </div>
        @can('create',[ \App\Models\TitleApproval::class, $scholar])
        <a href="{{ route('scholars.title-approval.request', $scholar) }}" class="ml-auto inline-flex items-center space-x-1 btn btn-magenta px-2 py-1">
            <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
           <span>Request</span>
        </a>
        @elsecan('request',[ \App\Models\TitleApproval::class, $scholar])
        <x-modal.trigger modal="title-approval-requirements-modal"
            class="ml-auto inline-flex items-center space-x-1 btn btn-magenta px-2 py-1">
            <x-feather-icon name="plus" class="w-4 h-4"></x-feather-icon>
            <span>Request</span>
        </x-modal.trigger>
        @endcan
    </div>
    <div class="flex-1 px-4 border rounded-lg flex items-center">
    @if($scholar->titleApproval)
        @can('view', [$scholar->titleApproval, $scholar])
        <a href="{{ route('scholars.title-approval.show', [$scholar, $scholar->titleApproval]) }}" target="_blank" class="inline-flex items-center underline px-3 py-1 bg-magenta-100 text-magenta-800 rounded font-bold mx-2">
            <x-feather-icon name="link" class="h-4 mr-2"> Title Approval Form </x-feather-icon>
            Title Approval Form
        </a>
        @endcan
        @if ($scholar->titleApproval->recommended_title)
            <p class="font-bold p-1 ml-2 flex flex-wrap"> Title recommended:
                <span class="text-gray-500 px-2">
                    {{ $scholar->titleApproval->recommended_title }}
                </span>
            </p>
        @endif
        <p class="px-3 py-1 m-2 text-center flex items-center font-lg font-bold border border-solid rounded-full ml-auto
            {{ $scholar->titleApproval->status->getContextCSS() }}">
            {{ $scholar->titleApproval->status }}
        </p>
        @can('recommend', [$scholar->titleApproval, $scholar])
        <form action="{{ route('scholars.title-approval.recommend', [$scholar, $scholar->titleApproval]) }}" method="POST">
            @method('PATCH') @csrf_token
            <button type="submit" class="px-4 py-2 mr-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-bold">
                Recommend
            </button>
        </form>
        @endcan
        @can('approve', [$scholar->titleApproval, $scholar])
        <x-modal.trigger class="px-4 py-2 mr-1 bg-green-500 hover:bg-green-600 text-white rounded font-bold"
            modal="approve-scholar-title-modal">
            Approve
        </x-modal.trigger>
        @endcan
    @else
        <p class="py-2 px-6 text-gray-700 font-bold">Not yet applied.</p>
    @endif
    </div>
</div>
