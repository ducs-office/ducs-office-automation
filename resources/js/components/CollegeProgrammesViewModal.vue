<template>
    <modal name="college-programmes-view-modal" height="auto" @before-open="beforeOpen">
        <div class = "p-6">
            <li class="text-lg font-bold mb-8 form-label">Programmes</li>
            <ul class="w-full bg-gray-200 text-gray-700 px-3 py-2 border border-gray-200 rounded" v-for="programme in college_programmes"
                :key="programme.id" :value="programme.id"
            >{{programme.code}} - {{(programme.name)}}</ul>
        </div>
    </modal>
</template>
<script>
    export default {
        data() {
            return {
                college_programmes: []
            }
        },
        methods: {
            beforeOpen(event) {
                if(!event.params.college_programmes){
                    return false;
                }
                this.college_programmes = event.params.college_programmes;
            }
        }
    }
</script>
