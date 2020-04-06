<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Co-Supervisor</h2>
            <form :action="route('staff.cosupervisors.update', data('cosupervisor', ''))" method="POST">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="name" class="w-full form-label mb-1">
                        Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="text" name="name" class="w-full form-input" :value="data('cosupervisor.name')">
                </div>
                <div class="mb-2">
                    <label for="email" class="w-full form-label mb-1">
                        Email <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="email" name="email" class="w-full form-input" :value="data('cosupervisor.email')">
                </div>
                <div class="mb-2">
                    <label for="designation" class="w-full form-label mb-1">
                        Designation <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="text" name="designation" class="w-full form-input" :value="data('cosupervisor.designation')">
                </div>
                <div class="mb-2">
                    <label for="affiliation" class="w-full form-label mb-1">
                        Affiliation <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="text" name="affiliation" class="w-full form-input" :value="data('cosupervisor.affiliation')">
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
