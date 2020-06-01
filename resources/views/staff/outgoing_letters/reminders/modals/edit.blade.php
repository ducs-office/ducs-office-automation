<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <div class="p-6">
            <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
            <form :action="route('staff.reminders.update', data('reminder', ''))" method="POST" enctype="multipart/form-data">
                @csrf_token @method('PATCH')
                <div class="my-4 flex">
                    <v-file-input id="pdf" name="attachments[]" accept="application/pdf" class="flex-1 form-input overflow-hidden mr-2"
                        placeholder="Choose a PDF file" required>
                        <template v-slot="{ label }">
                            <div class="w-full inline-flex items-center">
                                <x-feather-icon name="upload" class="h-4 mr-2 text-gray-700 flex-shrink-0"></x-feather-icon>
                                <span v-text="label" class="truncate"></span>
                            </div>
                        </template>
                    </v-file-input>
                    <v-file-input id="scan" name="attachments[]" accept="image/*" class="flex-1 form-input overflow-hidden"
                        placeholder="Choose a Scanned Image" required>
                        <template v-slot="{ label }">
                            <div class="w-full inline-flex items-center">
                                <x-feather-icon name="upload" class="h-4 mr-2 text-gray-700 flex-shrink-0"></x-feather-icon>
                                <span v-text="label" class="truncate"></span>
                            </div>
                        </template>
                    </v-file-input>
                </div>
                <div>
                    <button class="btn btn-magenta is-sm">Submit</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>
