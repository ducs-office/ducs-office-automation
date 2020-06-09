<form action="{{ route('profiles.update', $user) }}" method="POST" class="space-y-3">
@csrf_token
@method('PATCH')
<div class="space-y-1 mb-2">
    <label for="edit-phone" class="w-full form-label mb-1 @error('phone', 'update') text-red-500 @enderror">
        Contact Number
    </label>
    <input id="edit-phone" type="tel" name="phone"
        class="w-full form-input @error('phone', 'update') border-red-500 hover:border-red-700 @enderror"
        value="{{ old('phone', $user->phone) }}">
    @error('phone', 'update')
    <p class="text-red-500">{{ $message }}</p>
    @enderror 
</div>
<div class="space-y-1 mb-2">
    <label for="edit-address" class="w-full form-label mb-1 @error('address', 'update') text-red-500 @enderror">
        Address
    </label>
    <textarea id="edit-address" name="address"
    class="w-full form-textarea @error('address', 'update') border-red-500 hover:border-red-700 @enderror">{{old('address', $user->address)}}
    </textarea>
    @error('address', 'update')
    <p class="text-red-500">{{ $message }}</p>
    @enderror 
</div>
<div class="mt-5">
    <button type="submit" class="btn btn-magenta">Update</button>
</div>
</form>