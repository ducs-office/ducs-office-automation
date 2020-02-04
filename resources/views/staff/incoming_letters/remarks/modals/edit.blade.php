<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h3 class="font-bold text-lg mb-2">Update Letter Remark</h3>
            <form :action="route('staff.remarks.update', data('remark', ''))" method="POST">
                @csrf_token @method('PATCH')
                <div class="my-4">
                    <textarea name="description" v-text="data('remark.description')" class="w-full form-input"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
