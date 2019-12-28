<template>
    <modal name="programme-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Programme</h2>
            <form :action="route('programmes.update', programme)" method="POST" class="flex items-end">
                <slot></slot>
                <div class="flex-1 mr-2">
                    <label for="programme_code" class="w-full form-label">Programme Code<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" v-model="programme.code">
                </div>
                <div class="flex-1 mr-2">
                    <label class="w-full form-label">Date (w.e.f)<span class="h-current text-red-500 text-lg">*</span></label>
                    <input type="date" name="wef" class="w-full form-input" v-model="programme.wef">
                </div>
                <div class="flex-1 mr-5">
                    <label for="programme_name" class="w-full form-label">Programme<span class="h-current text-red-500 text-lg">*</span></label>
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
                wef: '',
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
