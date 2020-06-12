<form method="POST" 
    class="space-y-4" action="{{ route('scholars.profile.update', $scholar) }}">
    @method('PATCH')
    @csrf_token
    <livewire:add-remove
        view="livewire.education-list"
        :items="collect($scholar->education_details)->map->toArray()->toArray()"
        items-name="educationItems" 
        :data="[
            'degrees' => $degrees->toArray(),
            'institutes' => $institutes->toArray(),
            'subjects' => $subjects->toArray(),
        ]"
        :new-item="[
            'institute' => '',
            'degree' => '',
            'subject' => '',
            'year' => '',
        ]"/>

    @if($errors->update->hasAny('education_details.*', 'education_details'))
        @foreach (collect($errors->update->messages())->flatten(2) as $message)
            <p class="text-red-600 text-sm">
                {{ $message }}
            </p>
        @endforeach
    @endif
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
