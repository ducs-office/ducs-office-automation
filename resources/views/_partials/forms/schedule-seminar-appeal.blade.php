<form action="{{ route('scholars.pre-phd-seminar.schedule', [ $scholar, $scholar->prePhdSeminar])}}" method="post">
    @csrf_token
    @method("PATCH")
    <div class="flex items-center mb-2">
        <label for="scheduled_on" class="form-label @error('scheduled_on') text-red-500 @enderror">
            Schedule
            <span class="text-red-600">*</span>
        </label>
        <div>
            <input type="datetime-local" name="scheduled_on" 
                class="form-input ml-2 @error('scheduled_on') border-red-500 hover:border-red-700" @enderror
                value="{{ old('scheduled_on', $scholar->prePhdSeminar->scheduled_on) }}"
                required>
            @error('scheduled_on')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-magenta ml-2">Schedule</button>
</form>