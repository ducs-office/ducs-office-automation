<form action="{{ route('scholars.courseworks.complete', [$scholar, $course]) }}"
    method="POST" class="space-y-3" enctype="multipart/form-data">
    @csrf_token @method("PATCH")
    <div class="space-y-1">
        <label for="completed_on" class="form-label w-full
            @error('completed_on') text-red-500 @enderror">
            Date of Completion
            <span class="text-red-600 font-bold">*</span>
        </label>
        <input type="date" name="completed_on"
            class="form-input w-full" id="completed_on"
            value="{{ old('completed_on') }}"
        >
        @error('completed_on')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="marksheet" class="form-label w-full
            @error('marksheet') text-red-500 @enderror">
            Upload Marksheet
            <span class="text-red-600 font-bold">*</span>
        </label>
        <input id="marksheet" type="file" name="marksheet"
            class="form-input w-full" accept="application/pdf,image/*"
        >
        @error('marksheet')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <button class="mt-5 bg-green-500 hover:bg-green-600 text-white text-sm py-2 rounded font-bold btn">
        Mark Completed
    </button>
</form>