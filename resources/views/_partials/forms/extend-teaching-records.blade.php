<x-form method="PATCH" action="{{ route('teaching-records.extend') }}">
    <p class="mb-4">We've started accepting submissions, you can extend the deadline.</p>
    <div class="flex items-end">
        <div class="flex-1">
            <label for="start_date" class="w-full form-label mb-1">Start Date</label>
            <input type="date" disabled id="start_date" class="w-full form-input"
                value="{{ $startDate->format('Y-m-d') }}">
        </div>
        <div class="flex-1 ml-2">
            <label for="end_date" class="w-full form-label mb-1">Deadline</label>
            <input type="date" name="extend_to" id="end_date" class="w-full form-input"
                value="{{ $endDate->format('Y-m-d') }}">
        </div>
        <div class="ml-1">
            <button class="btn btn-magenta" type="submit">Extend Deadline</button>
        </div>
    </div>
</x-form>
