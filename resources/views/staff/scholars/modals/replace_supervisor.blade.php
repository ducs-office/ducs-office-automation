<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Replace Supervisor</h2>
            <form :action="route('staff.scholars.replace_supervisor', data('scholar', ''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <select class="form-input w-full block" name="supervisor_profile_id" id="supervisor" required>
                        <option class="text-gray-600" selected disabled value="">Select Supervisor</option>
                        @foreach ($supervisors as $name => $id)
                            <option class="text-gray-600" value="{{ $id }}" v-if="data('scholar.supervisor_profile_id') !== {{ $id }} "> 
                                {{ $name }} 
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <button class="btn btn-magenta">Replace</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>