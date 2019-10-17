<template>
    <modal name="reminder-update-modal" height="auto" @before-open="beforeOpen">
        <div class= "p-6">
            <h3 class="font-bold text-lg mb-2">Upload Letter Reminder</h3>
            <form :action="`/reminders/${reminder.id}`" method="POST" enctype="multipart/form-data">
                <slot></slot>
                <div class="my-4 flex">
                    <input type="file" name="pdf" accept="application/pdf" id="pdf"/>
                    <input type="file" name="scan" accept="image/* , application/pdf" id="scan"/>
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