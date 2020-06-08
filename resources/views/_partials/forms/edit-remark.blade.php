<x-form :action="route('staff.remarks.update', $remark)" method="PATCH">
    <div class="flex items-center mb-2">
        <img src="{{ $remark->user->avatar_url }}" alt="{{ $remark->user->name }}" width="32" height="32"
            class="w-8 h-8 rounded-full mr-4">
        <div>
            <h4 class="text-gray-900 font-bold text-lg leading-none">{{ $remark->user->name }}</h4>
            <span class="text-xs text-gray-700 font-bold">
                {{ $remark->updated_at->format('M d, Y h:i a') }}
            </span>
        </div>
    </div>
    <div class="my-4">
        <textarea name="description" class="w-full form-input">{{ $remark->description }}</textarea>
    </div>
    <div>
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</x-form>
