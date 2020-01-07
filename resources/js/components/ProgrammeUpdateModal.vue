<template>
    <modal name="programme-update-modal" height="auto" @before-open="beforeOpen">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-8">Update Programme</h2>
            <form :action="route('programmes.update', programme)" method="POST" class="px-6">
                <slot></slot>
                <div class="mb-2">
                    <label for="programme_code" class="w-full form-label">Programme Code<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" v-model="programme.code">
                </div>
                <div class="mb-2">
                    <label class="w-full form-label">Date (w.e.f)<span class="h-current text-red-500 text-lg">*</span></label>
                    <input type="date" name="wef" class="w-full form-input" v-model="programme.wef">
                </div>
                <div class="mb-2">
                    <label for="programme_name" class="w-full form-label">Programme<span class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input" v-model="programme.name">
                </div>
                <div class="mb-2">
                    <label for="programme_course" class="w-full form-label">Courses</label>
                    <div class="overflow-y-auto overflow-x-hidden h-32 border">
                            <div class="flex justify-between mt-1 px-3 py-1" v-for=" course in courses " :key="course.id">
                                <label :for="`course-${ course.id }`" >{{ course.name }} ({{ course.code }}) </label>
                                <input 
                                    :id="`course-${ course.id }`"
                                    type="checkbox"
                                    name="courses[]"
                                    :value=course.id 
                                    :checked="course.programme_id != null"/>
                            </div>
                    </div>
                </div>
                <div class="mb-2">
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
            } ,
            courses: []
        }
    },
    methods: {
        beforeOpen(event) {
            if(!event.params.programme || !event.params.programme.id) {
                return false;
            }

            this.programme = event.params.programme;
            this.courses = event.params.courses;
        }
    }
}
</script>
