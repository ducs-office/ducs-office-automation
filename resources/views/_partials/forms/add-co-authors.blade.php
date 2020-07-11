@can('create', [App\Model\CoAuthor::class, $publication])
<form action="{{ route('publications.co-authors.store', $publication) }}" enctype="multipart/form-data"
    method="POST" class="space-y-2">
    @csrf_token
    <div class="flex w-full">
        <div class="space-x-1 flex-1">
            <input type="text" name="name" id="name" class="form-input w-full" placeholder="Name (required)" required
            >
        </div>
        <div class="space-x-1 ml-2 w-1/2">
            <x-input.file name="noc" id="noc"
                class="w-full form-input inline-flex items-center "
                accept="application/pdf, image/*"
                placeholder="Upload NOC"/>
        </div>
        <button type="submit" class="btn btn-magenta ml-2">Add</button>
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
