<form action="{{ route('scholars.pre-phd-seminar.finalize', ['scholar' => $scholar, 'prePhdSeminar' => $scholar->prePhdSeminar])}}" method="post">
    @csrf_token
    @method("PATCH")
    <div class="flex items-center mb-2">
        <label for="finalized_title" class="form-label @error('finalized_title') text-red-500 @enderror">
            Finalized Title
            <span class="text-red-600">*</span>
        </label>
        <div>
            <input type="text" name="finalized_title" 
            class="form-input ml-2 @error('finalized_title') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('finalized_title', $scholar->prePhdSeminar->finalized_title) }}"
            required>
            @error('finalized_title')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-magenta ml-2">Finalize</button>
</form>