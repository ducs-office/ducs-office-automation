<x-multiselect.livewire :name="$name" wire:model="value" queryModel="searchQuery">
    @foreach($users as $user)
    <div class="px-4 py-3" value="{{ $user->id }}">
        <div class="flex items-center">
            <img src="{{ $user->getAvatarUrl() }}" alt="{{ $user->name }}'s Avatar"
                class="w-6 h-6 rounded-full mr-3 overflow-hidden">
            <div class="leading-none">
                <div class="leading-4">{{ $user->name }}</div>
                <div class="text-xs opacity-75">{{ $user->email }}</div>
            </div>
        </div>
    </div>
    @endforeach
</x-multiselect.livewire>
