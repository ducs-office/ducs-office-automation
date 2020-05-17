<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update College Teacher</h2>
            <form :action="route('staff.scholars.update', data('Scholar', ''))" method="POST">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="first_name" class="w-full form-label mb-1">
                        First Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="first_name" type="text" name="first_name" class="w-full form-input" :value="data('Scholar.first_name')">
                </div>
                <div class="mb-2">
                    <label for="last_name" class="w-full form-label mb-1">
                        Last Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="last_name" type="text" name="last_name" class="w-full form-input" :value="data('Scholar.last_name')">
                </div>
                <div class="mb-2">
                    <label for="email" class="w-full form-label mb-1">
                        Email <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="email" type="email" name="email" class="w-full form-input" :value="data('Scholar.email')">
                </div>
                <div class="flex mb-2 w-full">
                    <div class="w-1/2 mr-2">
                        <label for="supervisor" class="w-full form-label">Supervisor
                            <span class="h-current text-red-500 text-lg">*</span>
                        </label>
                        <select class="form-input w-full block" name="supervisor_profile_id" id="supervisor" required>
                            @foreach ($supervisors as $id => $name)
                                <option class="text-gray-600" value="{{ $id }}" :selected="data('Scholar.supervisor_profile_id') === {{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="cosupervisor" class="w-full form-label">Co-Supervisor
                            <span class="h-current text-red-500 text-lg">*</span>
                        </label>
                        <input type="hidden" name="cosupervisor_profile_type">
                        <select class="form-input w-full block" name="cosupervisor_profile_id" id="cosupervisor" required
                            onchange="cosupervisor_profile_type.value = this.options[this.selectedIndex].getAttribute('cosupervisor_profile_type')">

                            <option class="text-gray-600 selected" selected disabled value="">Select Co-Supervisor</option>
                            @foreach ($supervisors as $name => $id)
                                <option class="text-gray-600" value="{{ $id }}" cosupervisor_profile_type="App\Models\SupervisorProfile">
                                    {{ $name }}
                                </option>
                            @endforeach
                            @foreach ($cosupervisors as $name => $id)
                                <option class="text-gray-600" value="{{ $id }}" cosupervisor_profile_type="App\Models\Cosupervisor">
                                    {{ $name }}
                                </option>
                            @endforeach
                            <option class="text-gray-600" value=""> No cosupervisor assigned </option>
                        </select>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
