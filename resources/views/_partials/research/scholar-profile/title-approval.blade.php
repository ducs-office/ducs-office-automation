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
        @can('requestTitleApproval', \App\Models\ScholarAppeal::class)      
        <a href="{{ route('scholars.title_approval.request', $scholar) }}" class="btn btn-magenta is-sm -ml-4 my-2">
           Request For Title Appoval
        </a>
        @endcan
    </div>
    @if($scholar->titleApprovalAppeal())
    <div class="flex-1 border rounded-lg m-2 flex items-center">
        @can('viewTitleApprovalForm', [\App\Models\ScholarAppeal::class, $scholar])
        <a href="{{ route('scholars.title_approval.show', $scholar) }}" target="_blank" class="inline-flex items-center underline px-3 py-1 bg-magenta-100 text-magenta-800 rounded font-bold mx-2">
            <x-feather-icon name="link" class="h-4 mr-2"> Title Approval Form </x-feather-icon>
            Title Approval Form
        </a>
        @endcan
        @if ($scholar->recommended_title)
            <p class="font-bold p-1 ml-2 flex flex-wrap"> Title recommended: 
                <span class="text-gray-500 px-2">
                    {{ $scholar->recommended_title }}
                </span>
            </p>
        @endif
        <p class="px-3 py-1 m-2 text-center flex items-center font-lg font-bold border border-4 border-solid rounded-full ml-auto
            {{ $scholar->titleApprovalAppeal()->status->getContextCSS() }}">
            {{ $scholar->titleApprovalAppeal()->status }}
        </p>
        @can('respond', $scholar->titleApprovalAppeal())
        <form action="{{ route('scholars.title_approval.approve', [$scholar, $scholar->titleApprovalAppeal()]) }}" method="POST" 
        class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white rounded font-bold">
            @method('PATCH') @csrf_token
            <button type="submit"> 
                Approve
            </button>
        </form>
        @endcan
        @can('markComplete', $scholar->titleApprovalAppeal())
        <button class="px-4 py-2 mr-1 bg-green-500 hover:bg-green-600 text-white rounded font-bold" 
            x-on:click="$modal.show('mark-complete-modal')">
            Mark Complete
        </button>
        <x-modal name="mark-complete-modal" height="auto">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Add Recommended Title</h3>
                <form action="{{ route('scholars.title_approval.mark_complete', [$scholar, $scholar->titleApprovalAppeal()]) }}" method="POST"
                    class="px-6" enctype="multipart/form-data">
                    @csrf_token @method('PATCH')
                    <label for="recommended_title" class="mb-1 w-full form-label">Recommended Title
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="flex items-center w-full">
                        <input type="text" name="recommended_title" id="recommended_title" class="mr-1 flex-1 form-input" required>
                        <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none ml-1">Mark Complete</button>
                    </div>
                </form>
            </div>
        </x-modal>
        @endcan
    </div>
    @endif
</div>
