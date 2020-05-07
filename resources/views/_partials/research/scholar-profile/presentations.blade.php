<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Presentations
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        @if(auth()->guard('scholars')->check() && auth()->guard('scholars')->id() === $scholar->id)
        <div class="mt-3 text-right">
            <a class="btn btn-magenta" href="{{ route('scholars.presentation.create') }}">
                New
            </a>
        </div>
        @endif
    </div>
    <div class="flex-1">
        @include('research.scholars.presentations.index', [
            'presentations' => $scholar->presentations,
            'eventTypes' => $eventTypes,
        ])
    </div>
</div>
