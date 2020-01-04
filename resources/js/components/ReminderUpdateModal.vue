<template>
    <modal name="reminder-update-modal" height="auto" @before-open="beforeOpen">
        <div class= "p-6">
            <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
            <form :action="route('reminders.update', reminder)" method="POST" enctype="multipart/form-data">
                <slot></slot>
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
    </modal>
</template>
<script>
export default {
    data() {
        return {
            reminder: {
                id: ''
            }
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.reminder || !event.params.reminder.id) {
                return false;
            }

            this.reminder = event.params.reminder;
        }
    }
}
</script>
