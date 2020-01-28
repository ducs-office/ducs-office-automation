<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
            <form :action="route('reminders.update', data('reminder', ''))" method="POST" enctype="multipart/form-data">
                @csrf_token @method('PATCH')
                <div class="my-4 -mx-2 flex">
                    <div class="mx-2">
                        <label for="pdf" class="w-full form-label mb-1">Upload PDF copy</label>
                        <input type="file" name="attachments[]" accept="image/*, application/pdf" class="w-full">
                    </div>
                    <div class="mx-2">
                        <label for="scan" class="w-full form-label mb-1">Upload scanned copy</label>
                        <input type="file" name="attachments[]" accept="image/*, application/pdf" class="w-full">
                    </div>
                </div>
                <div>
                    <button class="btn btn-magenta is-sm">Submit</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
