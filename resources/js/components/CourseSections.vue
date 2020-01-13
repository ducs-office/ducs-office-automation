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