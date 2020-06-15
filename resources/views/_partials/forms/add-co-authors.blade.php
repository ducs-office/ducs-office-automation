@can('create', [App\Model\CoAuthor::class, $publication])
<form action="{{ route('publications.co-authors.store', $publication) }}"
    method="POST" class="space-y-3 flex">
    @csrf_token
    <div class="flex w-full">
        <div class="space-y-1 flex-1">
            <input type="text" name="name" id="name" class="form-input w-full" placeholder="Name (required)">
        </div>
        <div class="space-y-1 ml-2 flex-1">
            <x-input.file name="noc" id="noc"
                class="w-full form-input inline-flex items-center"
                accept="application/pdf, image/*"
                placeholder="Upload NOC"/>
        </div>
    </div>
    <button type="submit" class="btn btn-magenta ml-2">Add</button>
</form>
@endcan