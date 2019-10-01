<template>
    <modal name="paper-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Paper</h2>
            <form :action="`/papers/${paper.id}`" method="POST">
                <slot></slot>
                <div class="mb-2">
                    <label for="paper_code" class="w-full form-label mb-1">Unique Paper Code</label>
                    <input id="paper_code" type="text" name="code" class="w-full form-input" v-model="paper.code">
                </div>
                <div class="mb-2">
                    <label for="paper_name" class="w-full form-label mb-1">Paper Name</label>
                    <input id="paper_name" type="text" name="name" class="w-full form-input" v-model="paper.name">
                </div>
                <div class="mb-5">
                    <label for="paper_course" class="w-full form-label mb-1">Course</label>
                    <select id="paper_course" name="course_id" class="w-full form-input" v-model="paper.course_id">
                        <option v-for="(course, id) in courses" :key="id" :value="id" v-text="course"></option>
                    </select>
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
            paper: {
                id: '',
                code: '',
                name: '',
                course_id: null,
            },
            courses: []
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.paper || !event.params.paper.id) {
                return false;
            }
            
            this.paper = event.params.paper;
            this.courses = event.params.courses;
        }
    }
}
</script>