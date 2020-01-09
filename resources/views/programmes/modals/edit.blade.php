<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Programme</h2>
            <form :action="route('programmes.update', data('programme', ''))" method="POST" class="flex items-end">
                @csrf_token @method('PATCH')
                <div class="flex-1 mr-2">
                    <label for="programme_code" class="w-full form-label">
                        Programme Code <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" :value="data('programme.code')">
                </div>
                <div class="flex-1 mr-2">
                    <label class="w-full form-label">
                        Date (w.e.f) <span class="h-current text-red-500 text-lg">*</span>
                    </label>
                    <input type="date" name="wef" class="w-full form-input" :value="data('programme.wef')">
                </div>
                <div class="flex-1 mr-5">
                    <label for="programme_name" class="w-full form-label">Programme<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input" :value="data('programme.name')">
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
