<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Replace Scholar's Advisory Committee</h2>
            <form :action="route('research.scholars.advisory_committee.replace', data('scholar', ''))" method="POST">
                @csrf_token 
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">
                        Supervisor
                    </label>
                    <input type="text" class="w-full form-input" disabled :value="data('scholar.advisory_committee.supervisor')">
                </div>
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">
                        Co-Supervisor
                    </label>
                    <input type="text" class="w-full form-input" disabled :value="data('scholar.advisory_committee.cosupervisor')">
                </div>
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">
                        Faculty Teacher <span class="text-red-600">*</span>
                    </label>
                    <select name="faculty_teacher" class="w-full form-input">
                        <option value="" selected> ---- Choose Faculty Teacher ---- </option>
                        @foreach ($faculty as $facultyTeacher)
                            <option value="{{$facultyTeacher->name}}">
                                {{ $facultyTeacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label for="external[]" class="w-full form-label mb-1">
                        External
                    </label>
                    <div class="flex w-full mb-2 items-baseline">
                        <label for="external[name]" class="form-label mr-5 w-1/4 text-gray-600">
                            Name <span class="text-red-600">*</span>
                        </label>
                        <input type="text" class="w-full form-input" name="external[name]">
                    </div>
                    <div class="flex w-full mb-2 items-baseline">
                        <label for="external[designation]" class="form-label mr-5 w-1/4 text-gray-600">
                            Designation <span class="text-red-600">*</span>
                        </label>
                        <input type="text" class="w-full form-input" name="external[designation]">
                    </div>
                    <div class="flex w-full mb-2 items-baseline">
                        <label for="external[affiliation]" class="form-label mr-5 w-1/4 text-gray-600">
                            Affiliation <span class="text-red-600">*</span>
                        </label>
                        <input type="text" class="w-full form-input" name="external[affiliation]">
                    </div>
                    <div class="flex w-full mb-2 items-baseline">
                        <label for="external[email]" class="form-label mr-5 w-1/4 text-gray-600">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="text" class="w-full form-input" name="external[email]">
                    </div>
                    <div class="flex w-full mb-2 items-baseline">
                        <label for="external[phone_no]" class="form-label mr-5 w-1/4 text-gray-600">
                            Phone Number
                        </label>
                        <input type="text" class="w-full form-input" name="external[phone_no]">
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
