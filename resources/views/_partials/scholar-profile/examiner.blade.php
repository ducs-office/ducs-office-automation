<div class="page-card p-6">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="flag" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Examiner Status</h2>
        </div>
        @can('create', [\App\Models\ScholarExaminer::class, $scholar])
            <form action="{{ route('scholars.examiner.apply', $scholar) }}" method="POST" class="ml-auto">
                @csrf_token
                <button type="submit" class="btn btn-magenta px-2 py-1">Request</button>
            </form>
        @endcan
    </div>
    <div class="flex items-center px-4 py-3 border rounded-lg">
        @if ($scholar->examiner === null)
            <p class="px-4 text-center text-gray-700 flex ml-auto font-bold">Not Applied</p>
        @endif
        @if(optional($scholar->examiner)->applied_on)
            <div class="text-center">
                <h3 class="font-bold">Applied On </h3>
                <h3 class="text-gray-800 mt-1"> {{$scholar->examiner->applied_on->format('d F, Y')}}</h3>
            </div>
        @endif
        @if(optional($scholar->examiner)->recommended_on)
        <div class="ml-8 text-center">
            <h3 class="font-bold">Recommended On </h3>
            <h3 class="text-gray-800 mt-1"> {{$scholar->examiner->recommended_on->format('d F, Y')}}</h3>
        </div>
        @endif
        @if(optional($scholar->examiner)->approved_on)
            <div class="ml-8 text-center">
                <h3 class="font-bold">Approved On </h3>
                <h3 class="text-gray-800 mt-1"> {{$scholar->examiner->approved_on->format('d F, Y')}}</h3>
            </div>
        @endif
        <div class="flex ml-auto items-baseline">
            @if(optional($scholar->examiner)->status)
            <div class="flex">
                <p class="px-3 py-1 text-center flex items-center font-lg font-bold border border-4 border-solid rounded-full
                    {{ $scholar->examiner->status->getContextCSS()}}">
                    {{ $scholar->examiner->status}}
                </p>
            </div>
            @else

            @endif
            <div class="flex ml-2">
                @can('recommend', [$scholar->examiner, $scholar])
                <form action="{{ route('scholars.examiner.recommend', [$scholar, $scholar->examiner]) }}" method="POST"
                    class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white rounded font-bold">
                    @method('PATCH') @csrf_token
                    <button type="submit">
                        Recommend
                    </button>
                </form>
                @endcan
                @can('approve', [$scholar->examiner, $scholar])
                <form action="{{ route('scholars.examiner.approve', [$scholar, $scholar->examiner]) }}" method="POST"
                    class="px-4 py-2 mr-1 bg-green-500 hover:bg-green-600 text-white rounded font-bold">
                    @method('PATCH') @csrf_token
                    <button type="submit">
                        Approve
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
