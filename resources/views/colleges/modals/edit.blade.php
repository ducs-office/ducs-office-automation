<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update College</h2>
            <form :action="route('colleges.update', data('college', ''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="items-baseline">
                    <div class="mb-2">
                        <label for="college_code" class="w-full form-label">College Code<span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_code" type="text" name="code" class="w-full form-input" :value="data('college.code')">
                    </div>
                    <div class="mb-2">
                        <label for="college_name" class="w-full form-label mb-1">College<span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_name" type="text" name="name" class="w-full form-input" :value="data('college.name')">
                    </div>
                    <div class="mb-2">
                        <label for="programme" class="w-full form-label mb-1">Programmes<span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <select name="programmes[]" id="programme"
                            class="w-full form-input" multiple>
                            @foreach ($programmes as $programme)
                                <option :value="{{ $programme->id }}"
                                    :selected="data('college_programmes', []).includes({{$programme->id}})">{{ $programme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn btn-magenta">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </template>
</v-modal>
