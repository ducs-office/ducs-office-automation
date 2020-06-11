<form action="{{ route('scholars.leaves.store', $scholar) }}" method="POST" enctype="multipart/form-data">
    @csrf_token
    @if ($extensionId)        
    <input type="hidden" name="extended_leave_id" value="{{ $extensionId }}">
    @endif
    <div class="flex mb-2">
        <div class="flex-1 mr-2">
            <label for="from_date" class="w-full form-label mb-1 @error('from') text-red-500 @enderror">
                From Date
                <span class="text-red-600 font-bold">*</span>
            </label>
            @if ($extensionFromDate) 
            <div class="w-full form-input cursor-not-allowed bg-gray-400 hover:bg-gray-400">
                <span> {{ $extensionFromDate }} </span>
                <input type="hidden" name="from" value="{{ $extensionFromDate ?? '' }}">
            </div>
            @else
            <input id="from_date" type="date" name="from"
                placeholder="From Date"
                class="w-full form-input @error('from') border-red-500 hover:border-red-700 @enderror" 
                value="{{ old('from', '') }}"
                required>
            @error('from')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
            @endif
        </div>
        <div class="flex-1 ml-2">
            <label for="to_date" class="w-full form-label mb-1 @error('to') text-red-500 @enderror">
                To Date
                <span class="text-red-600 font-bold">*</span>
            </label>
            <input type="date" name="to" id="to_date" placeholder="To Date" 
            class="w-full form-input @error('to') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('to', '') }}"
            required>
            @error('to')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <div class="mb-2">
        <label for="reason" class="w-full form-label mb-1">
            Reason <span class="text-red-600 font-bold @error('reason') text-red-500 @enderror">*</span>
        </label>
        <select id="leave_reasons" name="reason" class="w-full form-select @error('reason') border-red-500 hover:border-red-700 @enderror" onchange="
            if(reason.value === 'Other') {
                reason_text.style = 'display: block;';
            } else {
                reason_text.style = 'display: none;';
            }">
            <option value="Maternity/Child Care Leave">Maternity/Child Care Leave</option>
            <option value="Medical">Medical</option>
            <option value="Duty Leave">Duty Leave</option>
            <option value="Deregistration">Deregistration</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" name="reason_text" 
        class="w-full form-input mt-2 hidden @error('reason') border-red-500 hover:border-red-700 @enderror" 
        placeholder="Please specify..."
        value="{{ old('reason', '') }}">
        @error('reason')
        <p class="text-red-500"> {{ $message }} </p>
        @enderror
    </div>
    <div class="mb-2">
        <label for="application" class="w-full form-label mb-1 @error('application') text-red-500 @enderror">
            Attach Application
            <span class="text-red-600 font-bold">*</span>
        </label>
        <x-input.file name="application" id="application"
        class="w-full form-input inline-flex items-center {{ $errors->has('application') ? 'border-red-500 hover:border-red-700' : '' }}"
        accept="application/pdf, image/*"
        placeholder="Upload Application"
        required/>
        @error('application')
            <p class="text-red-500"> {{ $message }} </p>
        @enderror
    </div>
    <button type="submit" class="px-5 btn btn-magenta text-sm">Add</button>
</form>
