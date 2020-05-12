{{-- Supervisors --}}
<div class="page-card p-6 overflow-visible flex space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Supervisor
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <ul class="flex flex-col mt-4 flex-1 border rounded-lg overflow-hidden divide-y divide-y-reverse">
        @foreach ($scholar->supervisors as $supervisor)
            <li class="px-5 p-2">
                @include('research.scholars.partials.supervisor', [
                    'supervisor' => $supervisor
                ])
            </li>
        @endforeach
    </ul>
</div>
