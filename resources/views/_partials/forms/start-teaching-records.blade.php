<x-form method="POST" action="{{ route('teaching-records.start') }}">
    @if($startDate && $startDate < now())
        <p class="mb-4">
            We previously accepted submission from
            <span>{{ $startDate->format('d M, Y') }}</span>
            till <span>{{ $endDate->format('d M, Y') }}</span>
        </p>
    @elseif($endDate && $endDate > now())
        <p class="mb-4">
            We will start accepting submission from
            <span>{{$startDate->format('d M, Y')}}</span>
            till <span>{{$endDate->format('d M, Y')}}</span>
        </p>
    @else
        <p class="mb-4">We never accepted details in the past.</p>
    @endif
    <div class="flex items-end space-x-2">
        <div class="flex-1 space-y-1">
            <label for="start-date-field"
                class="w-full form-label @error('start_date') text-red-500 @enderror">
                Start From <span class="text-red-500">*</span>
            </label>
            @error('start_date')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
            <input id="start-date-field" type="date" name="start_date"
                class="w-full form-input @error('start_date') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('start_date', ($startDate ?? now())->format('Y-m-d')) }}"
                required>
        </div>
        <div class="flex-1 space-y-1">
            <label for="end-date-field"
                class="w-full form-label @error('end_date') text-red-500 @enderror">
                Deadline <span class="text-red-500">*</span>
            </label>
            @error('end_date')
               <p class="text-red-500">{{ $message }}</p>
            @enderror
            <input id="end-date-field" type="date" name="end_date"
                class="w-full form-input @error('end_date') border-red-500 hover:border-red-700 @enderror"
                value="{{ old('end_date', ($endDate ?? now()->addMonth())->format('Y-m-d')) }}"
                required>
        </div>
        <button class="btn btn-magenta" type="submit">
            Schedule Submission
        </button>
    </div>
</x-form>
