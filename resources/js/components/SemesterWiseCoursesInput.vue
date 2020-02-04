<template>
<div>
    <draggable v-if="courses.length" :list="courses" group="courses" class="flex flex-wrap p-2 border rounded mb-4">
        <div class="min-w-1/2 p-1"
            v-for="course in courses"
            :key="course.id">
            <div class="bg-magenta-700 text-white px-2 py-1 cursor-move rounded shadow-inner truncate"
                :title="`[${course.code}]: ${course.name}`">
                <span v-text="`${course.code}:`"></span>
                <span v-text="course.name"></span>
            </div>
        </div>
    </draggable>
    <div v-else class="text-sm text-gray-600 font-bold border rounded p-2 px-4 mb-4">
        <span v-if="dataCourses.length">All Programmes assigned!</span>
        <span>No Courses. Please enter programme code for list of relevant courses.</span>
    </div>
    <div class="flex flex-wrap -mx-2">
        <div v-for="semester in count" :key="semester" class="w-1/2 px-2 py-1">
            <label class="w-full form-label mb-1">Semester {{semester}}: Courses</label>
            <draggable
                @change="e => updateSemesterCourses(e, semester)"
                :list="semesterWiseCourses[semester]"
                group="courses" class="p-2 border rounded min-h-12">
                <div class="min-w-1/2 p-1"
                v-for="course in semesterWiseCourses[semester]"
                :key="course.id">
                    <input v-if="name" type="hidden" :name="`${name}[${semester}][]`" :value="course.id" >
                    <div class="bg-magenta-700 text-white px-2 py-1 cursor-move rounded shadow-inner truncate"
                        :title="`[${course.code}]: ${course.name}`">
                        <span v-text="`${course.code}:`"></span>
                        <span v-text="course.name"></span>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</div>
</template>
<script>
import Draggable from 'vuedraggable';
export default {
    components: {Draggable},
    props: {
        name: {default: null},
        dataCourses: {required: true, type: Array},
        count: {default: 1},
        value: {type: Object, default: null}
    },
    data() {
        return {
            semesterWiseCourses: {},
            courses: []
        }
    },
    watch: {
        dataCourses() {
            this.courses = this.dataCourses;
        }
    },
    methods: {
        updateSemesterCourses(event, semester) {
            this.$forceUpdate();
        }
    },
    created() {
        this.courses = this.dataCourses;

        for(let sem=1; sem<=this.count; sem++) {
            if(this.value && sem in this.value) {
                this.semesterWiseCourses[sem] = this.value[sem].map(
                    value => {
                        const index = this.courses.findIndex(course => course.id == value);
                        return this.courses.splice(index, 1)[0];
                    }
                );
            } else {
                this.semesterWiseCourses[sem] = new Array();
            }
        }
    }
}
</script>
