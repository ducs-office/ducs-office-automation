<template>
    <modal name="reminder-update-modal" height="auto" @before-open="beforeOpen">
        <div class= "p-6">
            <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
            <form :action="`/reminders/${reminder.id}`" method="POST" enctype="multipart/form-data">
                <slot></slot>
                <div class="my-4 flex">
                    <div class="mx-2">
                        <input type="file" name="pdf" accept="application/pdf" class="w-full mb-2">
                        <label for="pdf" class="w-full form-label">Upload PDF copy</label>
                    </div>
                    <div class="mx-2">
                        <input type="file" name="scan" accept="image/*, application/pdf" class="w-full mb-2">
                        <label for="scan" class="w-full form-label">Upload scanned copy</label>
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