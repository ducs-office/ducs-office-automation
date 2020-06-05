<form action="{{ route('staff.programmes.update', $programme) }}" method="POST" class="space-y-3">
    @csrf_token @method('PATCH')
    <div class="space-y-1">
        <label for="programme-code"
            class="w-full form-label @error('code') text-red-500 @enderror">
            Programme Code <span class="text-red-500">*</span>
        </label>
        <input id="programme-code" type="text" name="code"
            class="w-full form-input @error('code') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('code', $programme->code) }}"
            required>
        @error('code')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="programme-data"
            class="w-full form-label @error('wef') text-red-500 @enderror">
            Date (w.e.f) <span class="text-red-500">*</span>
        </label>
        <input id="programme-data" type="date" name="wef"
            class="w-full form-input @error('wef') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('wef', $programme->wef->format('Y-m-d')) }}"
            required>
        @error('wef')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="programme-name"
            class="w-full form-label @error('name') text-red-500 @enderror">
            Name <span class="text-red-500">*</span>
        </label>
        <input id="programme-name" type="text" name="name"
            class="w-full form-input @error('name') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('name', $programme->name) }}"
            required>
        @error('name')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="programme-type"
            class="w-full form-label @error('type') text-red-500 @enderror">
            Type <span class="text-red-500">*</span>
        </label>
        <select id="programme-type" name="type"
            class="w-full form-select @error('type') border-red-500 hover:border-red-700 @enderror"
            required>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ old('type', $programme->type) == $type }}>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')
           <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
