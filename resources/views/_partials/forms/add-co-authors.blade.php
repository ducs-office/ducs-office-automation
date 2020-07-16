@can('create', [App\Model\CoAuthor::class, $publication])
<form name="add-co-author-form-{{ $publication->id }}" action="{{ route('publications.co-authors.store', $publication) }}" enctype="multipart/form-data"
    method="POST" class="space-y-2">
    @csrf_token
    <div class="flex w-full space-x-2">
        <input type="text" name="name" id="{{ $publication->id }}-name" class="form-input flex-1" placeholder="Name (required)" required>
        @auth('scholars')
        <x-input.file name="noc" id="{{ $publication->id }}-noc"
            class="flex-1 form-input inline-flex items-center"
            accept="application/pdf,image/*"
            placeholder="Upload NOC"/>
        @endauth
        <button type="submit" class="btn btn-magenta">Add</button>
    </div>
    <div>
        @error('name')
            <p class="text-red-500">
                {{ $message }}
            </p>
        @enderror
        @error('noc')
            <p class="text-red-500">
                {{ $message }}
            </p>
        @enderror
    </div>
</form>
@endcan
