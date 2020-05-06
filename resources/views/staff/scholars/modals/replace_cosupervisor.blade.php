<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Replace Co-Supervisor</h2>
            <form :action="route('staff.scholars.replace_cosupervisor', data('scholar', ''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="mb-2">
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
                <div class="mt-5">
                    <button class="btn btn-magenta">Replace</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>