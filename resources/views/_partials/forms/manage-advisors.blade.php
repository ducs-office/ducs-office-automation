<h2 class="text-lg font-bold mb-6">{{ $headerMessage }}</h2>
<x-form method="PATCH" class="space-y-4" action="{{ route( $routeName, $scholar) }}">
    <livewire:add-remove
        view="livewire.advisors-list"
        :items="$scholar->currentAdvisors->map->id->all()"
        items-name="advisors" />
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
    @error('advisors', 'update')
        <p class="text-red-500"> {{ $message }} </p>
    @enderror
    @error('advisors.*', 'update')
        <p class="text-red-500"> {{ $message }} </p>
    @enderror
</x-form>
