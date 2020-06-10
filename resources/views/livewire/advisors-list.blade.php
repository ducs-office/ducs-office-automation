<div class="space-y-2">
    <div class="text-right">
        <button type="button" class="link" wire:click="add()">add more...</button>
    </div>
    @foreach($advisors as $index => $advisor)
    <div class="flex space-x-2">
        <livewire:typeahead-advisors name="advisors[]"
            :limit="15"
            :key="$index"
            placeholder="Select Advisor"
            search-placeholder="Search from available advisors..."
            :value="$advisor"/>
        <button type="button" class="p-2 group" wire:click="remove({{ $index }})">
            <x-feather-icon name="x" class="h-6 transform transition duration-150 group-hover:scale-110"></x-feather-icon>
        </button>
    </div>
    @endforeach
</div>
