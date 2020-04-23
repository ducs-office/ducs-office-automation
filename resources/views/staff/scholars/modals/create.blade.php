<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">Create Scholar</h2>
        <form action="{{ route('staff.scholars.store') }}" method="POST" class="px-6">
            @csrf_token
            <div class="mb-2">
                <label for="first_name" class="w-full form-label">First Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="first_name" type="text" name="first_name" class="w-full form-input"
                    placholder="Enter first name here..." required>
            </div>
            <div class="mb-2">
                <label for="last_name" class="w-full form-label">Last Name<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="last_name" type="text" name="last_name" class="w-full form-input"
                    placholder="Enter last name here..." required>
            </div>
            <div class="mb-2">
                <label for="email" class="w-full form-label">Email<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="email" type="email" name="email" class="w-full form-input"
                    placholder="Enter scholar's email here..." required>
            </div>
            <div class="flex mb-2 w-full">
                <div class="w-1/2 mr-2">
                    <label for="supervisor" class="w-full form-label">Supervisor
                        <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <select class="form-input w-full block" name="supervisor_profile_id" id="supervisor" required>
                        <option class="text-gray-600" selected disabled value="">Select Supervisor</option>
                        @foreach ($supervisors as $name => $id)
                            <option class="text-gray-600" value="{{ $id }}"> {{ $name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/2 ml-2">
                    <label for="cosupervisor" class="w-full form-label">Co-Supervisor
                        <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <select class="form-input w-full block" name="cosupervisor_id" id="cosupervisor" required>
                        <option class="text-gray-600 selected" selected disabled value="">Select Co-Supervisor</option>
                        @foreach ($cosupervisors as $name => $id)
                            <option class="text-gray-600" value="{{ $id }}"> {{ $name }} </option>
                        @endforeach
                        <option class="text-gray-600" value=""> No cosupervisor assigned </option>
                    </select>
                </div>
            </div>
            <div class="mt-5">
                <button class="btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
</v-modal>
