<div class="page-card p-6 flex overflow-visible space-x-6" x-data="{ showRequiredDocuments: false, showModal:false }">
    <div>
        <div class="w-64 pr-4 relative z-10 -ml-8 my-2">
            <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
                Pre PhD Seminar
            </h3>
            <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
                <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
            </svg>
        </div>
        @can('requestPhDSeminar', \App\Models\ScholarAppeal::class)
        @if ($scholar->isDocumentListComplete())
            <a href="{{ route('scholars.pre_phd_seminar.show', $scholar) }}"
            class="btn btn-magenta is-sm -ml-4 my-2"  @mouseenter.always="showRequiredDocuments = true" @mouseleave.always="showRequiredDocuments = false" >
                Request for Pre PhD seminar
            </a>
        @else    
            <button class="btn btn-magenta is-sm -ml-4 my-2" @mouseenter.always="showRequiredDocuments = true" @mouseleave.always="showRequiredDocuments = false" @click.always="showModal = true, showRequiredDocuments = false"> 
                Request for Pre PhD seminar
            </button>
        @endif
        @endcan 
    </div>
    @php
        $phdSeminarAppeal = $scholar->phdSeminarAppeal()->first();
    @endphp
    @if ($phdSeminarAppeal)
    
    <form id="patch-form" method="POST" class="w-0">
        @csrf_token @method("PATCH")
    </form>
    <div class="flex flex-1 items-center justify-between">
        <div class="mx-2"> 
            @if ($phdSeminarAppeal->applied_on)
                <p class="font-bold pl-2 m-1"> Applied On: <span class="font-medium"> {{ $phdSeminarAppeal->applied_on->format('d F Y') }}</span></p>
            @endif
            @if ($phdSeminarAppeal->response_date)
                <p class="font-bold pl-2 m-1"> Respond On: <span class="font-medium"> {{ $phdSeminarAppeal->response_date->format('d F Y') }}</span></p>
            @endif
        </div>
        @can('viewPhdSeminarForm', [\App\Models\ScholarAppeal::class, $scholar])
            <a href="{{ route('scholars.pre_phd_seminar.show', $scholar) }}" target="_blank" class="inline-flex items-center underline px-3 py-1 bg-magenta-100 text-magenta-800 rounded font-bold mx-2">
                <feather-icon name="link" class="h-4 mr-2"> Pre-Phd Seminar Form </feather-icon>
                Pre-PhD Seminar Form
            </a>
        @endcan
        <div class="mx-2">
            <div class="flex justify-center items-center px-4 mb-2">
                <feather-icon name="{{ $phdSeminarAppeal->status->getContextIcon() }}"
                    class="h-current {{ $phdSeminarAppeal->status->getContextCSS() }} mr-2" stroke-width="2.5"></feather-icon>
                <div class="capitalize">
                    {{ $phdSeminarAppeal->status }}
                </div>
            </div>
            @can('recommend', $phdSeminarAppeal)
                <button type="submit" form="patch-form"
                    class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                    formaction="{{ route('scholars.appeals.recommend', [$scholar, $phdSeminarAppeal]) }}">
                    Recommend
                </button>
            @endcan
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
        </div>
    </div>
    @endif
    <div class="bg-magenta-200 border text-magenta-800 border-magenta-800 m-2 flex-1 rounded-lg p-2" x-show.transition="showRequiredDocuments"> 
       <p class="font-bold mb-1"> Please make sure you have uploaded following documents before applying for Pre-Phd Seminar </p>
       <p>1. Copy of joining report(s)</p>
       <p>2. Letter of extension from BRS (if any)</p>
       <p>3. List of publications along with first page of publications 
           (include names of all authors;  Scopus Indexed, MR numbers, SCI Impact factor, if  any)</p>
       <p>4. Reprints/preprints/acceptance letter</p>
    </div>
    <div x-show.transition="showModal" style = "background-color: rgba(0,0,0,0.5)" :class="{'overflow-hidden absolute inset-0 z-10 flex items-center justify-center': showModal }">
        <div class="bg-white rounded Shadow-lg overflow-hidden w-auto flex items-center justify-center" @click.away="showModal = false"> 
            <div class="p-2 m-6 h-auto"> 
                <p class="text-lg mb-3 font-bold">Upload following document(s) before applying for Pre-PhD Seminar</p>
                @if (!$scholar->isJoiningLetterUploaded())
                    <li class="text-gray-600 font-bold m-2"> Copy of joining report(s) </li>
                @endif
                @if (!$scholar->isAcceptanceLetterUploaded())
                    <li class="text-gray-600 font-bold m-2">Reprints/preprints/acceptance letter </li>
                @endif
            </div>
        </div>
    </div>
</div>