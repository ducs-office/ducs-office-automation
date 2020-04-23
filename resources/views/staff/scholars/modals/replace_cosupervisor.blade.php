<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Replace Co-Supervisor</h2>
            <form :action="route('staff.scholars.replace_cosupervisor', data('scholar', ''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <select class="form-input w-full block" name="cosupervisor_id" id="cosupervisor" required>
                        <option class="text-gray-600 selected" selected disabled value="">Select Co-Supervisor</option>
                        @foreach ($cosupervisors as $name => $id)
                            <option class="text-gray-600" value="{{ $id }}" v-if="data('scholar.cosupervisor_id') !== {{ $id }}">
                                {{ $name }} 
                            </option>
                        @endforeach
                        <option class="text-gray-600" value="" v-if="data('scholar.cosupervisor_id')">
                            No cosupervisor assigned 
                        </option>
                    </select>
                </div>
                <div class="mt-5">
                    <button class="btn btn-magenta">Replace</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>