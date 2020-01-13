<template>
    <div class="relative">
        <div class="mb-2">
            <label for="programme_duration" class="w-full form-label">Duration (years)<span
                    class="h-current text-red-500 text-lg">*</span></label>
            <input id="programme_duration" type="number" name="duration" class="w-full form-input" v-model="years">
        </div>
        <transition name="flip">
            <div class="flex flex-wrap mb-2 -mx-2" v-if="semesters > 0">
                <div v-for="(semester,index) in semesters" :key="index"
                class="w-1/2 px-2 py-1">
                    <label :for="`semsester_courses_${index}`" class="w-full form-label">Semester {{semester}}: Courses</label>
                    <v-multi-typeahead
                        :id="`semester_courses_${index}`"
                        :name="`semester_courses[${index}][]`"
                        source="/api/courses"
                        find-source="/api/courses/{value}"
                        limit="5"
                        :value="semester in courses ? courses[semester] : []"
                        placeholder="Courses"
                        >
                    </v-multi-typeahead>
                </div>
            </div>
        </transition>
    </div>
</template> 
<script>
export default {
    props: {
        duration: {default: 3},
        semesterCourses: { 
            default: () => new Array()
        } 
    },
    data() {
        return {
            years: this.duration,
            courses: []
        };
    },
    computed: {
        semesters() {
            return this.years * 2;
        }
    },
    created() {
        if(this.semesterCourses.length < 1) {
            this.courses = new Array(this.semesters).fill(null).map(() => new Array());
        } else {
            this.courses = this.semesterCourses;
        }
    }
};
</script>         
<style>
.flip-enter-active,
.flip-leave-active {
  transition: all 0.3s;
}
.flip-enter,
.flip-leave-to {
  opacity: 0;
  transform-origin: 50% 0;
  transform: translateY(50%);
}
</style>