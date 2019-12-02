<template>
    <modal name="remark-update-modal" height="auto" @before-open="beforeOpen">
        <div class= "p-6">
            <h3 class="font-bold text-lg mb-2">Update Letter Remark</h3>
            <form :action="route('remarks.update', remark)" method="POST">
                <slot></slot>
                <div class="my-4">
                    <textarea name="description" v-model="remark.description" class="w-full form-input"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </modal>
</template>
<script>
export default {
    data() {
        return {
            remark: {
                description: ''
            }
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.remark || !event.params.remark.id) {
                return false;
            }

            this.remark = event.params.remark;
        }
    }
}
</script>
