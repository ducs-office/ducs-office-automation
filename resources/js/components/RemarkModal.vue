<template>
    <modal name = "remark-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <h2 class = "text-lg font-bold mb-8">Remarks</h2>
            <div class="px-6 py-2 hover:bg-gray-100 border-b justify-between">
                <div class="flex items-baseline mb-2" v-for="(remark,id) in remarks" :key="id" :value="id">
                    <h4 class="font-bold text-sm text-gray-600 w-24">{{remark.updated_at}}</h4>
                    <h4 class="font-bold text-lg  mr-2">{{ remark.description }}</h4>
                    <a
                        class="p-1 text-gray-500 hover:bg-gray-200 hover:text-blue-600 rounded mr-3" title="Edit">
                        <feather-icon name="edit-3" stroke-width="2.5" class="h-current">Edit</feather-icon>
                    </a>
                    <form :action="`/remark/${remark.id}/`" method="POST">
                    </form>
                </div>
            </div>
        </div>
    </modal>
</template>
<script>
export default {
    data() {
        return {
            letter: {
                id: ''
            },
            remarks: []
        }
    },
    methods: {
        beforeOpen(event) {
            this.letter = event.params.letter;
            this.remarks = event.params.remarks;
        },
        log() {
            console.log(this.letter.id);
            console.log(this.remarks);
        }
    }
}
</script>