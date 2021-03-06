<form x-data="{
        supervisor_id: '{{ old('supervisor_id', $supervisors->keys()->first()) }}',
        cosupervisor_id: '{{ old('cosupervisor_id', '') }}',
    }"
    action="{{ route('staff.scholars.store') }}" method="POST" class="space-y-3">
    @csrf_token
    <div class="flex space-x-2">
        <div class="flex-1 space-y-1">
            <label for="first_name"
                class="w-full form-label @error('first_name', 'create') text-red-500 @enderror">
                First Name <span class="text-red-500">*</span>
            </label>
            <input id="first_name" type="text" name="first_name"
                class="w-full form-input @error('first_name', 'create') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('first_name') }}"
                required>
            @error('first_name', 'create')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex-1 space-y-1">
            <label for="last_name"
                class="w-full form-label @error('last_name', 'create') text-red-500 @enderror">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input id="last_name" type="text" name="last_name"
                class="w-full form-input @error('last_name', 'create') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('last_name') }}"
                required>
            @error('last_name', 'create')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="space-y-1">
        <label for="scholar-email"
            class="w-full form-label @error('email', 'create') text-red-500 @enderror">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="scholar-email" type="email" name="email"
            class="w-full form-input @error('email', 'create') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('email') }}"
            required>
        @error('email', 'create')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex space-x-2">
        <div class="flex-1 space-y-1">
            <label for="scholar-registration_date"
                class="w-full form-label @error('registration_date', 'create') text-red-500 @enderror">
                Date of Registration <span class="text-red-500">*</span>
            </label>
            <input id="scholar-registration_date" type="date" name="registration_date"
                class="w-full form-input @error('registration_date', 'create') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('registration_date') }}"
                required>
            @error('registration_date', 'create')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex-1 space-y-1">
            <label for="scholar-term-duration"
                class="w-full form-label @error('term_duration', 'create') text-red-500 @enderror">
                Term Duration (in years) <span class="text-red-500">*</span>
            </label>
            <input id="scholar-term-duration" type="number" name="term_duration" min="1" max="7"
                class="w-full form-input @error('term_duration', 'create') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('term_duration') }}"
                required>
            @error('term_duration', 'create')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="flex space-x-2">
        <div class="flex-1 space-y-1">
            <label for="scholar-supervisor"
                class="w-full form-label @error('supervisor_id', 'create') text-red-500 @enderror">
                Supervisor <span class="text-red-500">*</span>
            </label>
            <select id="scholar-supervisor" name="supervisor_id"
                class="w-full form-select @error('supervisor_id', 'create') border-red-500 hover:border-red-700 @enderror"
                x-model="supervisor_id"
                required>
                @foreach($supervisors as $id => $supervisor)
                    <option value="{{ $id }}" x-bind:disabled="cosupervisor_id == {{ $id }}">{{ $supervisor }}</option>
                @endforeach
            </select>
            @error('supervisor_id', 'create')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex-1 space-y-1">
            <label for="scholar-cosuprevisor"
                class="w-full form-label @error('cosupervisor_id', 'create') text-red-500 @enderror">
                Cosupervisor
            </label>
            <select id="scholar-cosuprevisor" name="cosupervisor_id"
                class="w-full form-select @error('cosupervisor_id', 'create') border-red-500 hover:border-red-700 @enderror"
                x-model="cosupervisor_id">
                <option value="">None</option>
                @foreach($cosupervisors as $id => $cosupervisor)
                    <option value="{{ $id }}" x-bind:disabled="supervisor_id == {{ $id }}">{{ $cosupervisor }}</option>
                @endforeach
            </select>
            @error('cosupervisor_id', 'create')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="mt-5">
        <button class="btn btn-magenta">Create</button>
    </div>
</form>
