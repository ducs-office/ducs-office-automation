<form action="{{ route('scholars.profile.update', $scholar) }}" method="POST"
    class="space-y-3"
    x-data="{gender: '{{ old('gender', $scholar->gender) }}'}">
    @csrf_token @method('PATCH')
    <div class="space-y-1">
        <label for="gender"
            class="w-full form-label mb-1 @error('gender', 'update') text-red-500 @enderror">
            Gender
        </label>
        <select id="gender" name="gender" class="w-full form-select @error('gender', 'update') border-red-500 hover:border-red-700 @enderror"
            x-model="gender">
            <option value="" class="text-gray-600" selected disabled>Select your Gender</option>
            @foreach ($genders as $gender)
            <option value="{{ $gender }}"> {{ $gender }} </option>
            @endforeach
        </select>
        @error('gender', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="phone"
            class="w-full form-label mb-1 @error('phone', 'update') text-red-500 @enderror">
            Phone
        </label>
        <input id="phone" type="text" name="phone" class="w-full form-input @error('phone', 'update') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('phone', $scholar->phone) }}">
        @error('phone', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="address"
            class="w-full form-label mb-1 @error('address', 'update') text-red-500 @enderror">
            Address
        </label>
        <textarea id="address" name="address" class="w-full form-input @error('address', 'update') border-red-500 hover:border-red-700 @enderror">
            {{ old('address', $scholar->address) }}
        </textarea>
        @error('address', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div> 
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>