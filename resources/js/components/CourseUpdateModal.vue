<template>
    <modal name="course-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Course</h2>
            <form :action="`/courses/${course.id}`" method="POST">
                <slot></slot>
                <div class="mb-2">
                    <label for="course_code" class="w-full form-label mb-1">Unique Course Code</label>
                    <input id="course_code" type="text" name="code" class="w-full form-input" v-model="course.code">
                </div>
                <div class="mb-2">
                    <label for="course_name" class="w-full form-label mb-1">Course Name</label>
                    <input id="course_name" type="text" name="name" class="w-full form-input" v-model="course.name">
                </div>
                <div class="mb-5">
                    <label for="course_programme" class="w-full form-label mb-1">Programme</label>
                    <select id="course_programme" name="programme_id" class="w-full form-input" v-model="course.programme_id">
                        <option v-for="(programme, id) in programmes" :key="id" :value="id" v-text="programme"></option>
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
            course: {
                id: '',
                code: '',
                name: '',
                programme_id: null,
            },
            programmes: []
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.course || !event.params.course.id) {
                return false;
            }

            this.course = event.params.course;
            this.programmes = event.params.programmes;
        }
    }
}
</script>
