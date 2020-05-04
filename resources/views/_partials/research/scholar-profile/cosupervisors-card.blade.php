{{-- Cosupervisors --}}
<div class="page-card p-6 overflow-visible flex space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-6">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Cosupervisor
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <ul class="self-start flex flex-col-reverse mt-4 flex-1 border rounded-lg overflow-hidden divide-y divide-y-reverse">
        @php($sinceDate = $scholar->registerOn)
        @foreach ($scholar->old_cosupervisors as $old_cosupervisor)
        <li class="px-5 p-2">
            @include('research.scholars.partials.cosupervisor', [
                'cosupervisor' => (object)$old_cosupervisor
            ])
            @php($sinceDate = $old_cosupervisor['date'])
        </li>
        @endforeach
        <li class="px-5 p-2">
            @include('research.scholars.partials.cosupervisor', [
                'cosupervisor' => $scholar->cosupervisor
            ])
        </li>
    </ul>
</div>
