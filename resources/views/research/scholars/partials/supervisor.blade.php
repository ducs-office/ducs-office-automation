<div class="flex justify-between items-center">
    <div class="w-1/2">
        <p class="font-bold">{{ $supervisor->name }}</p>
        <div class="flex mt-1 items-center text-gray-700">
            <x-feather-icon name="at-sign" class="h-current">Email</x-feather-icon>
            <p class="ml-1 italic">{{ $supervisor->email }}</p>
        </div>
    </div>
    <p class="w-1/2 mr-4 font-bold"> {{ $supervisor->pivot->started_on->format('d F Y') }} - {{ optional($supervisor->pivot->ended_on)->format('d F Y') ?? 'Present' }}</p>
</div>
