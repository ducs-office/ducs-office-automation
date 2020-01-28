<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update College Teacher</h2>
            <form :action="route('teachers.update', data('Teacher', ''))" method="POST">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="first_name" class="w-full form-label mb-1">
                        First Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="first_name" type="text" name="first_name" class="w-full form-input" :value="data('Teacher.first_name')">
                </div>
                <div class="mb-2">
                    <label for="last_name" class="w-full form-label mb-1">
                        Last Name <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="last_name" type="text" name="last_name" class="w-full form-input" :value="data('Teacher.last_name')">
                </div>
                <div class="mb-2">
                    <label for="email" class="w-full form-label mb-1">
                        Email <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="email" type="email" name="email" class="w-full form-input" :value="data('Teacher.email')">
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
