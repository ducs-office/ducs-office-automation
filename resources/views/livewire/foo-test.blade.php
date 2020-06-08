<x-select.livewire wire:model="value" queryModel="searchQuery" :name="$name">
    @foreach($users as $user)
    <div class="px-4 py-3" value="{{ $user->id }}">
        <div class="flex items-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s Avatar"
                class="w-8 h-8 rounded-full mr-3 overflow-hidden">
            <div class="leading-none">
                <div class="leading-4">{{ $user->name }}</div>
                <div class="text-gray-600">{{ $user->email }}</div>
            </div>
        </div>
    </div>
    @endforeach
</x-select.livewire>
