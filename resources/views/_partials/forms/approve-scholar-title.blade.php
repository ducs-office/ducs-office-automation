<form action="{{ route('scholars.title-approval.approve', [$scholar, $scholar->titleApproval]) }}" method="POST"
    class="space-y-3" enctype="multipart/form-data">
    @csrf_token @method('PATCH')
    <label for="recommended_title" class="mb-1 w-full form-label
        @error('recommended_title') text-red-500 @enderror">
        Recommended Title <span class="text-red-600">*</span>
    </label>
    <div class="flex items-center w-full">
        <input type="text" name="recommended_title" id="recommended_title" 
        class="mr-1 flex-1 form-input @error('recommended_title') border-red-500 hover:border-red-700 @enderror" required
        >
        <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none ml-1">Approve</button>
    </div>
    @error('recommended_title')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
</form>