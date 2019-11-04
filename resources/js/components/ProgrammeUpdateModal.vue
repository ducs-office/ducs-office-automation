<template>
    <modal name="programme-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Programme</h2>
            <form :action="`/programmes/${programme.id}`" method="POST" class="flex items-end">
                <slot></slot>
                <div class="flex-1 mr-2">
                    <label for="programme_code" class="w-full form-label">Programme Code</label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" v-model="programme.code">
                </div>
                <div class="flex-1 mr-5">
                    <label for="programme_name" class="w-full form-label">Programme</label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input" v-model="programme.name">
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
            programme: {
                id: '',
                code: '',
                name: '',
            }
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.programme || !event.params.programme.id) {
                return false;
            }

            this.programme = event.params.programme;
        }
    }
}
</script>
