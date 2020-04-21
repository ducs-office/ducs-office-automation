<template>
    <div class="flex">
        <div class="w-1/4 mr-2">
            <select :name="`${name}[degree]`" class="form-input mr-2 h-8 p-1 w-full" 
            v-model="element.value.degree" @change="toggleDegree">

                <option v-for="(id, degree) in degreeChoices" :key="id" 
                :value="id" :slected="id == element.value.degree">{{ degree }}</option>
                <option :value="-1">Other</option>

            </select>
            <input type="text" :name="`typedDegrees[${index}]`" v-show="isInputDegreeVisible" class="form-input mt-2 h-8 w-full" placeholder="Please specify...">
        </div>
        <div class="w-1/4 mr-2">
            <select :name="`${name}[subject]`" class="form-input mr-2 h-8 p-1 w-full" 
            v-model="element.value.subject" @change="toggleSubject">

                <option v-for="(id, subject) in subjectChoices" :key="id" 
                :value="id" :slected="id == element.value.subject">{{ subject }}</option>
                <option :value="-1">Other</option>

            </select>
            <input type="text" :name="`typedSubjects[${index}]`" v-show="isInputSubjectVisible" class="form-input mt-2 h-8 w-full" placeholder="Please specify...">
        </div>
        <div class="w-1/4 mr-2">
            <select :name="`${name}[institute]`" class="form-input mr-2 h-8 p-1 w-full" 
            v-model="element.value.institute" @change="toggleInstitute">

                <option v-for="(id, institute) in instituteChoices" :key="id" 
                :value="id" :slected="id == element.value.institute">{{ institute }}</option>
                <option :value="-1">Other</option>

            </select>
            <input type="text" :name="`typedInstitutes[${index}]`" v-show="isInputInstituteVisible" class="form-input mt-2 h-8 w-full" placeholder="Please specify...">
        </div>
        <input type="text" :name="`${name}[year]`" v-model="element.value.year" class="form-input h-8 w-1/4">
    </div>
</template>



<script>
export default {

    props:{
        name: {deafult: null},
        subjectChoices: {required: true, type: Object},
        degreeChoices: {required: true, type: Object},
        instituteChoices: {required: true, type: Object},
        element: {required: true, type: Object},
        index: {required: true, type: Number}
    },
    data() {
        return {
            isInputSubjectVisible: false,
            isInputDegreeVisible: false,
            isInputInstituteVisible: false
        }
    },
    methods: {
        toggleSubject(event) {
            if(event.target.value == -1)
                this.isInputSubjectVisible = true;
            else
                this.isInputSubjectVisible = false;
        },
        toggleDegree(event) {
            if(event.target.value == -1)
                this.isInputDegreeVisible = true;
            else
                this.isInputDegreeVisible = false;
        },
        toggleInstitute(event) {
            if(event.target.value == -1)
                this.isInputInstituteVisible = true;
            else
                this.isInputInstituteVisible = false;
        }
    },
}
</script>
