<div class="flex justify-between items-center">
    <div class="w-1/2">
        <p class="font-bold">{{ $supervisor->name }}</p>
        <div class="flex mt-1 items-center text-gray-700">
            <feather-icon name="at-sign" class="h-current">Email</feather-icon>
            <p class="ml-1 italic">{{ $supervisor->email }}</p>
        </div>
    </div>
    <p class="w-1/2 mr-4 font-bold"> {{ $sinceDate }} - {{ $supervisor->date ?? 'Present' }}</p>
</div>
