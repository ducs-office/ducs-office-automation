<div class="flex justify-between items-center">
    <div class="w-1/2">
        @if($cosupervisor && $cosupervisor->name)
        <p class="font-bold"> {{ $cosupervisor->name }} </p>
        <p class="text-gray-700 mt-1"> {{ $cosupervisor->designation }} </p>
        <p class="text-gray-700 mt-1"> {{ $cosupervisor->affiliation }} </p>
        <div class="flex mt-1 items-center text-gray-700">
            <x-feather-icon name="at-sign" class="h-current">Email</x-feather-icon>
            <p class="ml-1 italic"> {{ $cosupervisor->email }} </p>
        </div>
        @else
        <p class="font-bold"> Cosupervisor Not Assigned </p>
        @endif
    </div>
    <p class="w-1/2 mr-4 font-bold"> {{ $cosupervisor->pivot->started_on->format('d F Y') }} - {{ optional($cosupervisor->pivot->ended_on)->format('d F Y') ?? 'Present' }}</p>
<div>
