<div class="page-card p-6 space-y-4">
    <div class="-mx-6 -mt-6 px-6 py-3 border-b mb-6 flex items-center">
        <div class="flex items-center space-x-2">
            <x-feather-icon name="monitor" class="h-4 w-4"></x-feather-icon>
            <h2 class="text-lg font-bold">Presentations</h2>
        </div>
        @can('create', App\Models\Presentation::class)
            <a class="ml-auto py-1 px-2 inline-flex items-center space-x-1 btn btn-magenta"
                href="{{ route('scholars.presentations.create', ['scholar' => $scholar]) }}">
                <x-feather-icon name="plus" class="h-4 w-4"></x-feather-icon>
                <span>New</span>
            </a>
        @endcan
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->presentations as $presentation)
                @include('_partials.list-items.presentation')
            @empty
                <li><p class="text-gray-600 flex justify-center font-bold py-3 items-center">No presentations to show!</p></li>
            @endforelse
        </ul>
    </div>
</div>
