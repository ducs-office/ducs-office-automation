<template>
    <modal name="course-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Course</h2>
            <form :action="`/courses/${course.id}`" method="POST" class="flex items-end">
                <slot></slot>
                <div class="flex-1 mr-2">
                    <label for="course_code" class="w-full form-label">Course Code</label>
                    <input id="course_code" type="text" name="code" class="w-full form-input" v-model="course.code">
                </div>
                <div class="flex-1 mr-5">
                    <label for="course_name" class="w-full form-label">Course</label>
                    <input id="course_name" type="text" name="name" class="w-full form-input" v-model="course.name">
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
            }
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.course || !event.params.course.id) {
                return false;
            }
            
            this.course = event.params.course;
        }
    }
}
</script>