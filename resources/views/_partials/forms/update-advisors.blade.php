<h2 class="text-lg font-bold mb-6">Update Scholar Advisor</h2>
<x-form method="PATCH" class="space-y-4" action="{{ route('research.scholars.advisors.update', $scholar) }}">
    <livewire:add-remove
        view="livewire.advisors-list"
        :items="$scholar->currentAdvisors->map->id->all()"
        items-name="advisors" />
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</x-form>
