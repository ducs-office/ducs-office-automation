<form action="{{ route('scholars.proposed_title.update', $scholar)}}" method="post">
    @csrf_token
    @method("PATCH")
    <div class="flex items-center">
        <label for="proposed_title" class="form-label @error('proposed_title') text-red-500 @enderror">
            Proposed Title
            <span class="text-red-600">*</span>
        </label>
        <div>
            <input type="text" name="proposed_title" 
            class="form-input ml-2 @error('proposed_title') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('proposed_title', $scholar->proposed_title) }}"
            required>
            @error('proposed_title')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-magenta ml-2">Update</button>
</form>