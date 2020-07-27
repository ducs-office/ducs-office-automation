<div class="page-card p-0 divide-y">
    <div class="px-6 pt-4 pb-3 flex items-center">
        <h4 class="text-xl font-bold">Current Teaching Details</h4>
    </div>
    <div class="px-6 py-4">
        <x-form method="POST" action="{{ route('teaching-details.store') }}">
            <div class="flex items-end space-x-2">
                @include('_partials.forms.add-teaching-detail')
            </div>
        </x-form>
    </div>
    <ul class="divide-y p-0">
        @forelse($currentTeachingDetails as $detail)
        <li class="px-6 py-4 relative">
            <div class="flex items-center space-x-4">
                <span
                    class="w-32 flex-none text-center px-4 py-1 rounded-full bg-magenta-100 text-magenta-800 text-sm font-bold">{{ $detail->course->code }}</span>
                <div class="flex-1">
                    <div class="text-lg capitalize font-bold">{{ $detail->course->name }}</div>
                    <div class="text-gray-700">
                        <span class="capitalize mr-1">{{ $detail->programmeRevision->programme->name }}</span>
                        (w.e.f. {{ $detail->programmeRevision->revised_at->year }})
                    </div>
                </div>
            </div>
            @can('delete', $detail)
            <x-form method="DELETE" action="{{ route('teaching-details.destroy', $detail) }}"
                class="absolute pr-6 right-0 inset-y-0 flex h-full items-center">
                <button class="group p-2 hover:text-red-600">
                    <x-feather-icon name="trash-2"
                        class="h-6 transform group-hover:scale-110 transition-transform duration-150"></x-feather-icon>
                </button>
            </x-form>
            @endcan
        </li>
        @empty
        <li class="py-4 px-6 text-gray-600 font-bold"> Nothing to see here. </li>
        @endforelse
    </ul>
    <div class="px-4 py-3">
        @can('create', App\Models\TeachingRecord::class)
        <form action="{{ route('teaching-records.submit') }}" method="POST" class="flex items-center space-x-4"
            onsubmit="return confirm('Are you sure you want to submit your teaching details?');">
            @csrf_token
            <button type="submit" class="btn btn-magenta"> Submit Details </button>
            <p class="font-bold text-gray-600">Please remove the courses you are not teaching currently, before
                submitting.</p>
        </form>
        @else
        <button disabled class="btn bg-gray-400 hover:bg-gray-400 cursor-not-allowed border-0">
            Submit Details
        </button>
        @endcan
    </div>
</div>
