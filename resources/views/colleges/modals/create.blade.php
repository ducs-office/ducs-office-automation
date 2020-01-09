<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">New College</h2>
        <form action="{{ route('colleges.index') }}" method="POST" class="items-end">
            @csrf_token
            <div class="items-baseline">
                <div class="mb-2">
                    <label for="college_code" class="w-full form-label">College Code<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_code" type="text" name="code" class="w-full form-input">
                </div>
                <div class="mb-2">
                    <label for="college_name" class="w-full form-label">College Name<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_name" type="text" name="name" class="w-full form-input">
                </div>
                <div class="mb-2">
                    <label for="programme" class="w-full form-label">Programmes <span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select name="programmes[]" id="programme" class="w-full form-input" multiple>
                        @foreach ($programmes as $programme)
                        <option value="{{$programme->id}}">{{$programme->code}} - {{ucwords($programme->name)}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </div>
        </form>
    </div>
</v-modal>
