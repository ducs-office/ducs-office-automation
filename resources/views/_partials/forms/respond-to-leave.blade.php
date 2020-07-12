<form action="{{ route('scholars.leaves.respond', [$scholar, $leave]) }}"
method="POST" enctype="multipart/form-data">
@csrf_token @method("PATCH")
    <div class="flex mb-2">
        <div class="flex-1 mr-2 items-baseline">
            <label for="response" class="form-label w-full mb-1 @error('response') text-red-500 @enderror">
                Response <span class="text-red-600 font-bold">*</span>
            </label>
            <select name="response" id="" class="form-select w-full @error('response') border-red-500 hover:border-red-700 @enderror" 
            required>
                <option value="">---Choose response type---</option>
                <option value="{{ App\Types\LeaveStatus::APPROVED }}">Approve</option>
                <option value="{{ App\Types\LeaveStatus::REJECTED }}">Reject</option>
            </select>
            @error('response')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
        <div class="flex-1 items-baseline">
            <label for="response_letter" class="form-label w-full mb-1 @error('response_letter') text-red-500 @enderror">
                Upload Response Letter
                <span class="text-red-600 font-bold">*</span>
            </label>
            <x-input.file name="response_letter" id="response_letter"
            class="w-full form-input inline-flex items-center {{ $errors->has('response_letter') ? 'border-red-500 hover:border-red-700' : '' }}"
            accept="application/pdf,image/*"
            placeholder="Upload Application"
            required/>
            @error('response_letter')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <button class="btn btn-magenta">
        Respond 
    </button>
</form>