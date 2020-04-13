<template>
    <div class="flex items-end mb-2 -mx-2" >
        <div class="flex-1 mx-2">
            <label :for="`programme-${index}`" class="w-full form-label mb-1">Programme {{ index + 1 }}</label>
            <select :id="`programme-${index}`" :name="`teaching_details[${index}][programme_revision_id]`"
                v-model="programmeRevisionId"
                class="w-full form-input">
                <option value="" selected>Choose a Programme</option>
                <option v-for="programme in programmes"
                    :value="programme.latest_revision_id" :key="programme.latest_revision_id"
                    :selected="programmeRevisionId == programme.latest_revision_id"
                    v-text="`[${programme.code}] ${programme.name}`">
                </option>
            </select>
        </div>
        <div class="flex-1 mx-2">
            <label :for="`course-${index}`" class="w-full form-label mb-1">Course {{ index + 1 }}</label>
            <select :id="`course-${index}`" :name="`teaching_details[${index}][course_id]`"
                class="w-full form-input" v-model="courseId">
                <option value="">Choose Course</option>
                <option v-for="course in courses"
                    :value="course.id" :key="course.id"
                    :selected="courseId == course.id"
                    v-text="`[${course.code}] ${course.name}`">
                </option>
            </select>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        index: {required: true, type: Number},
        programmes: {default: () => new Array()},
        value: {default: () => ({programme_revision_id: '', course_id: ''})},
    },
    data() {
        return {
            programmeRevisionId: this.value.programme_revision_id,
            courseId: this.value.course_id,
            courses: []
        };
    },
    watch: {
        programmeRevisionId() {
            this.fetchCourses()
                .then(({data}) => {
                    this.courses = data;
                    this.courseId = '';
                }).catch(() => {
                    this.courses = [];
                    this.courseId = '';
                });
        }
    },
    methods: {
        fetchCourses() {
            return axios.get('/api/programme-revisions/' + this.programmeRevisionId + '/courses');
        }
    },
    created() {
        if(this.programmeRevisionId != null) {
            this.fetchCourses()
                .then(({data}) => this.courses = data)
                .catch(() => this.courses = []);
        }
    }
}
</script>
