@extends('layouts.master', ['pageTitle' => 'Title Approval', 'scholar' => $scholar])
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
@can('approve', [$scholar->titleApproval, $scholar])
    <x-modal name="approve-scholar-title-modal" class="p-6 w-1/2" 
        :open="!$errors->default->isEmpty()">
        <h2 class="text-lg font-bold mb-8"> Approve Title - {{ $scholar->name }}</h2>
        @include('_partials.forms.approve-scholar-title')
    </x-modal>
@endcan
@endpush
@section('body')
<div class="page-card p-6 flex overflow-visible space-x-6">
    <div>
        <div class="w-64 pr-4 relative -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Title Approval
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div> 
        @can('create',[ \App\Models\TitleApproval::class, $scholar]) 
        <a href="{{ route('scholars.title-approval.request', $scholar) }}" class="btn btn-magenta is-sm -ml-4 my-2">
           Request
        </a>
        @elsecan('request',[ \App\Models\TitleApproval::class, $scholar])
        <x-modal.trigger  modal="title-approval-requirements-modal"
            class="btn btn-magenta is-sm -ml-4 my-2">
            Request
        </x-modal.trigger>
        @endcan
    </div>
    @if($scholar->titleApproval)
    <div class="flex-1 border rounded-lg m-2 flex items-center">
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
        <p class="px-3 py-1 m-2 text-center flex items-center font-lg font-bold border border-4 border-solid rounded-full ml-auto
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
    </div>
    @endif
</div>
@endsection
