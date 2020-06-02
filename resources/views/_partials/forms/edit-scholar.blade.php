<form x-data="{
        supervisor_id: '{{ old('supervisor_id', optional($scholar->currentSupervisor)->id) }}',
        cosupervisor_id: '{{ old('cosupervisor_id', optional($scholar->currentCosupervisor)->id) }}',
    }"
    action="{{ route('staff.scholars.update', $scholar) }}" method="POST" class="space-y-3">
    @csrf_token @method('PATCH')
    <div class="flex space-x-2">
        <div class="flex-1 space-y-1">
            <label for="first_name"
                class="w-full form-label @error('first_name') text-red-500 @enderror">
                First Name <span class="text-red-500">*</span>
            </label>
            <input id="first_name" type="text" name="first_name"
                class="w-full form-input @error('first_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('first_name', $scholar->first_name) }}"
                required>
            @error('first_name')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex-1 space-y-1">
            <label for="last_name"
                class="w-full form-label @error('last_name') text-red-500 @enderror">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input id="last_name" type="text" name="last_name"
                class="w-full form-input @error('last_name') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('last_name', $scholar->last_name) }}"
                required>
            @error('last_name')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="space-y-1">
        <label for="scholar-email"
            class="w-full form-label @error('email') text-red-500 @enderror">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="scholar-email" type="email" name="email"
            class="w-full form-input @error('email') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('email', $scholar->email) }}"
            required>
        @error('email')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="scholar-term-duration"
            class="w-full form-label @error('term_duration') text-red-500 @enderror">
            Term Duration (in years) <span class="text-red-500">*</span>
        </label>
        <input id="scholar-term-duration" type="number" name="term_duration" min="1" max="7"
            class="w-full form-input @error('term_duration') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('term_duration', $scholar->term_duration) }}"
            required>
        @error('term_duration')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex space-x-2">
        <div class="flex-1 space-y-1">
            <label for="scholar-supervisor"
                class="w-full form-label @error('supervisor_id') text-red-500 @enderror">
                Supervisor <span class="text-red-500">*</span>
            </label>
            <select id="scholar-supervisor" name="supervisor_id"
                class="w-full form-select @error('supervisor_id') border-red-500 hover:border-red-700 @enderror"
                x-model="supervisor_id"
                required>
                @foreach($supervisors as $id => $supervisor)
                    <option value="{{ $id }}" x-bind:disabled="cosupervisor_id == {{ $id }}">{{ $supervisor }}</option>
                @endforeach
            </select>
            @error('supervisor_id')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex-1 space-y-1">
            <label for="scholar-cosuprevisor"
                class="w-full form-label @error('cosupervisor_id') text-red-500 @enderror">
                Cosupervisor
            </label>
            <select id="scholar-cosuprevisor" name="cosupervisor_id"
                class="w-full form-select @error('cosupervisor_id') border-red-500 hover:border-red-700 @enderror"
                x-model="cosupervisor_id">
                <option value="" selected>None</option>
                @foreach($cosupervisors as $id => $cosupervisor)
                    <option value="{{ $id }}" x-bind:disabled="supervisor_id == {{ $id }}">{{ $cosupervisor }}</option>
                @endforeach
            </select>
            @error('cosupervisor_id')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="mt-5">
        <button class="btn btn-magenta">Update</button>
    </div>
</form>
