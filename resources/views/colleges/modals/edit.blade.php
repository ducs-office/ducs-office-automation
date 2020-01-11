<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update College</h2>
            <form :action="route('colleges.update', data('college', ''))" method="POST" class="items-end">
                @csrf_token @method('PATCH')
                <div class="flex items-end mb-2">
                    <div class="w-32 mr-1">
                        <label for="college_code" class="w-full form-label mb-1">College Code <span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_code" type="text" name="code" class="w-full form-input" :value="data('college.code')">
                    </div>
                    <div class="flex-1 ml-1">
                        <label for="college_name" class="w-full form-label mb-1">College Name<span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_name" type="text" name="name" class="w-full form-input" :value="data('college.name')">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="college_website" class="w-full form-label mb-1">Website<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_website" type="text" name="website" class="w-full form-input"
                        :value="data('college.website', 'http://')" required>
                </div>
                <div class="mb-2">
                    <label for="college_address" class="w-full form-label mb-1">Address<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <textarea id="college_address" name="address" rows="3" class="w-full form-input"
                        required v-text="data('college.address')"></textarea>
                </div>
                <div class="mb-2">
                    <label for="programme" class="w-full form-label mb-1">Programmes <span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select name="programmes[]" id="programme" class="w-full form-input" multiple>
                        @foreach ($programmes as $programme)
                        <option value="{{$programme->id}}"
                            :selected="data('college_programmes', []).includes({{ $programme->id }})">
                            {{$programme->code}} - {{ucwords($programme->name)}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="inline-block bg-magenta-700 text-white -ml-6 w-auto mt-4 mb-2 shadow">
                    <h5 class="px-4 py-2">Principal Details</h5>
                </div>
                <div class="mb-2">
                    <label for="college_principal_name" class="w-full form-label mb-1">Principal Name<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_principal_name" type="text" name="principal_name"
                        class="w-full form-input" :value="data('college.principal_name')" required>
                </div>
                <div class="flex items-end mb-2">
                    <div class="flex-1 mr-1">
                        <label for="college_principal_phone1" class="w-full form-label mb-1">Phone <span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_principal_phone1" type="text" name="principal_phones[]" class="w-full form-input"
                            :value="data('college.principal_phones.0', '')" required>
                    </div>
                    <div class="flex-1 ml-1">
                        <label for="college_principal_phone2" class="w-full form-label mb-1">Alternate Phone</label>
                        <input id="college_principal_phone2" type="text" name="principal_phones[]" class="w-full form-input"
                            :value="data('college.principal_phones.1', '')">
                    </div>
                </div>
                <div class="flex items-end mb-2">
                    <div class="flex-1 mr-1">
                        <label for="college_principal_email1" class="w-full form-label mb-1">Email <span
                                class="h-current text-red-500 text-lg">*</span></label>
                        <input id="college_principal_email1" type="email" name="principal_emails[]" class="w-full form-input"
                            :value="data('college.principal_emails.0', '')" required>
                    </div>
                    <div class="flex-1 ml-1">
                        <label for="college_principal_email2" class="w-full form-label mb-1">Alternate Email</label>
                        <input id="college_principal_email2" type="email" name="principal_emails[]" class="w-full form-input"
                            :value="data('college.principal_emails.1', '')">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Create</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
