<div class="space-y-2">
    <div class="flex">
        <label for="co_authors[]" class="form-label mb-1">Co-Author(s)</label>
        <div class="ml-auto">
            <button type="button" class="link" wire:click="add()">add more...</button>
        </div>
    </div>
    @forelse($coAuthors as $index => $coAuthor)
    <div class="flex space-x-2">
        <input type="text" x-model="'{{$coAuthor['name']}}'" 
            name="co_authors[{{$index}}][name]" 
            class="form-input mr-2" 
            placeholder="Co-Author's name">
        <x-input.file name="co_authors[{{$index}}][noc]" id="noc"
            class="w-full form-input inline-flex items-center"
            accept="application/pdf,image/*"
            placeholder="Upload NOC"
            />
        <button type="button" class="p-2 group" wire:click="remove({{ $index }})">
            <x-feather-icon name="x" class="h-6 transform transition duration-150 group-hover:scale-110"></x-feather-icon>
        </button>
    </div>
    @empty
    <div class="flex space-x-2 rounded border p-2">
        <p class="text-gray-600">Add multiple Co-authors by clicking on 'add more'.</p>
    </div>
    @endforelse
</div>
