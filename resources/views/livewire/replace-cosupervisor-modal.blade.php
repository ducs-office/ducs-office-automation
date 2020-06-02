<x-modal name="{{ $modalName }}" class="min-w-1/3 p-6" :open="$showModal">
    @if($showModal)
    <h2 class="text-lg font-bold mb-8 flex items-center space-x-4">
        <img class="h-8 w-8 bg-gray-400 rounded-full overflow-hidden flex-shrink-0"
            {{-- TODO: replace with avatar URL --}}
            src="#"
            alt="{{ $scholar->name }}'s Avatar" />
        <span>Replace {{ $scholar->first_name }}'s Cosupervisor</span>
    </h2>
    <form x-data="{cosupervisor_id: '{{ old('cosupervisor_id', optional($scholar->currentCosupervisor)->id) }}'}"
        action="{{ route('staff.scholars.cosupervisor.replace', $scholar) }}" method="POST" class="space-y-4">
        @csrf_token @method('PATCH')
        <div class="space-y-1">
            <label for="replace-cosupervisor" class="form-label">Replace Cosupervisor with</label>
            <select id="replace-cosupervisor"
                class="form-select w-full block"
                name="cosupervisor_id"
                x-model="cosupervisor_id">
                <option selected value="">None</option>
                @foreach ($cosupervisors as $id => $name)
                    <option value="{{ $id }}"
                        @if(optional($scholar->currentSupervisor)->id == $id) disabled @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn btn-magenta">Replace Cosupervisor</button>
        </div>
    </form>
    @else
    <p>Loading...</p>
    @endif
</x-modal>
