<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update College</h2>
            <form :action="route('colleges.update', data('college', ''))" method="POST" class="flex items-end">
                @csrf_token @method('PATCH')
                <div class="flex-1 mr-2">
                    <label for="college_code" class="w-full form-label">College Code<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_code" type="text" name="code" class="w-full form-input" :value="data('college.code')">
                </div>
                <div class="flex-1 mr-5">
                    <label for="college_name" class="w-full form-label">College<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="college_name" type="text" name="name" class="w-full form-input" :value="data('college.name')">
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
