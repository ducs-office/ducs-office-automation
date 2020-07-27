<form action="{{ route('scholars.advisory-meetings.store', $scholar) }}" method="POST"
    class="px-6" enctype="multipart/form-data">
    @csrf_token
    <div class="flex space-x-2">
        <div class="mb-2 space-y-1 w-1/2">
            <label for="date" class="mb-1 w-full form-label @error('date', 'advisoryMeeting') text-red-500 @enderror"> Date
                <span class="text-red-600">*</span>
            </label>
            <input id="date" name="date" type="date" class="w-full form-input @error('date', 'advisoryMeeting') border-red-500 hover:border-red-700 @enderror"
             value="{{old('date')}}"
             required>
            @error('date', 'advisoryMeeting')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
        <div class="mb-2 space-y-1 w-1/2">
            <label for="minutes_of_meeting" class="mb-1 w-full form-label @error('minutes_of_meeting', 'advisoryMeeting') text-red-500 @enderror"> Upload Minutes of Meetings
                <span class="text-red-600">*</span>
            </label>
            <x-input.file name="minutes_of_meeting" id="minutes_of_meeting"
                class="w-full form-input inline-flex items-center {{ $errors->has('minutes_of_meeting') ? 'border-red-500 hover:border-red-700' : '' }}"
                accept="application/pdf,image/*"
                placeholder="Upload Minutes Of Meetings"
                required/>
            @error('minutes_of_meeting', 'advisoryMeeting')
                <p class="text-red-500"> {{ $message }} </p>
            @enderror
        </div>
    </div>
    <button type="submit" class="px-5 btn btn-magenta text-sm">Add</button>
</form>
