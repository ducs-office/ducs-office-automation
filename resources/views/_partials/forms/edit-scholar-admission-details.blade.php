<form action="{{ route('scholars.profile.update', $scholar) }}" method="POST"
    class="space-y-3"
    x-data="{
        category: '{{ old('category', $scholar->category) }}',
        admission_mode: '{{ old('admission_mode', $scholar->admission_mode) }}',
        funding: '{{ old('funding', $scholar->funding) }}'
    }">
    @csrf_token @method('PATCH')
    <div class="space-y-1">
        <label for="category"
            class="w-full form-label mb-1 @error('category', 'update') text-red-500 @enderror">
            Category
        </label>
        <select id="category" name="category" class="w-full form-select @error('category', 'update') border-red-500 hover:border-red-700 @enderror"
            x-model="category">
            <option value="" class="text-gray-600" selected disabled>Select your category</option>
            @foreach ($categories as $category)
            <option value="{{ $category }}"> {{ $category }} </option>
            @endforeach
        </select>
        @error('category', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="enrolment_id"
            class="w-full form-label mb-1 @error('enrolment_id', 'update') text-red-500 @enderror">
            Enrollment ID
        </label>
        <input id="enrolment_id" type="text" name="enrolment_id" class="w-full form-input @error('enrolment_id', 'update') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('enrolment_id', $scholar->enrolment_id) }}">
        @error('enrolment_id', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="registration_date"
            class="w-full form-label mb-1 @error('registration_date', 'update') text-red-500 @enderror">
            Date of Registration
        </label>
        <input id="registration_date" type="date" name="registration_date" class="w-full form-input @error('registration_date', 'update') border-red-500 hover:border-red-700 @enderror"
            value="{{ old('registration_date', optional($scholar->registration_date)->format('Y-m-d')) }}">
        @error('registration_date', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="admission_mode"
            class="w-full form-label mb-1 @error('admission_mode', 'update') text-red-500 @enderror">
            Admission Mode
        </label>
        <select id="admission_mode" name="admission_mode" class="w-full form-select @error('admission_mode', 'update') border-red-500 hover:border-red-700 @enderror"
            x-model="admission_mode">
            <option value="" class="text-gray-600" selected disabled>Select your admission mode</option>
            @foreach ($admissionModes as $admissionMode)
            <option value="{{ $admissionMode }}"> {{ $admissionMode }} </option>
            @endforeach
        </select>
        @error('admission_mode', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="funding"
            class="w-full form-label mb-1 @error('funding', 'update') text-red-500 @enderror">
            Funding
        </label>
        <select id="funding" name="funding" class="w-full form-select @error('funding', 'update') border-red-500 hover:border-red-700 @enderror"
            x-model="funding">
            <option value="" class="text-gray-600" selected disabled>Select your funding type</option>
            @foreach ($fundings as $funding)
            <option value="{{ $funding }}"> {{ $funding }} </option>
            @endforeach
        </select>
        @error('funding', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="space-y-1">
        <label for="research_area"
            class="w-full form-label mb-1 @error('research_area', 'update') text-red-500 @enderror">
            Area of Research
        </label>
        <textarea id="research_area" name="research_area" class="w-full form-input @error('research_area', 'update') border-red-500 hover:border-red-700 @enderror">
            {{ old('research_area', $scholar->research_area) }}
        </textarea>
        @error('research_area', 'update')
            <p class="text-red-500">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-5">
        <button type="submit" class="btn btn-magenta">Update</button>
    </div>
</form>
