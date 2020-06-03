<x-modal name="{{ $modalName }}" class="min-w-1/3 p-6" :open="$showModal">
    @if($showModal)
    <h2 class="text-lg font-bold flex items-center space-x-4 mb-8">
        <img class="h-8 w-8 bg-gray-400 rounded-full overflow-hidden flex-shrink-0"
            src="{{ $scholar->getAvatarUrl() }}"
            alt="{{ $scholar->name }}'s Avatar" />
        <span>Replace {{ $scholar->first_name }}'s Supervisor</span>
    </h2>
    <form x-data="{supervisor_id: '{{ old('supervisor_id', optional($scholar->currentSupervisor)->id) }}'}"
        action="{{ route('staff.scholars.supervisor.replace', $scholar) }}" method="POST" class="space-y-4">
        @csrf_token @method('PATCH')
        <div class="space-y-1">
            <label for="replace-supervisor" class="form-label">Replace Supervisor with</label>
            <select id="replace-supervisor" class="form-select w-full block" name="supervisor_id" required x-model="supervisor_id">
                @foreach ($supervisors as $id => $name)
                    <option class="text-gray-600" value="{{ $id }}"
                        @if(optional($scholar->currentCosupervisor)->id == $id) disabled @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn btn-magenta">Replace Supervisor</button>
        </div>
    </form>
    @else
    <p>Loading...</p>
    @endif
</x-modal>
