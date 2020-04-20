<template>
    <div class="flex">
        <input type="text" :name="`education[${index}][degree]`" v-model="element.value.degree" class="form-input mr-2 h-8 w-1/4">
        <div class="w-1/4 mr-2">
            <select :name="`${name}[subject]`"  :data-index="`${index}`"
                class="form-input mr-2 h-8 p-1 w-full" v-model="element.value.subject"
                @change="e => toggleSubject(e.target.value)">

                <option v-for="(id, subject) in subjectChoices" :key="id" 
                :value="id" :selected="id == element.value.subject">{{ subject }}</option>
                <option value="-1">Other</option>

            </select>
            <input type="text" :name="`subject[${index}]`" v-if="isInputSubjectVisible" class="form-input mt-2 h-8 w-full" placeholder="Please specify...">
        </div>
        <input type="text" :name="`${name}[institute]`" v-model="element.value.institute" class="form-input mr-2 h-8 w-1/4">
        <input type="text" :name="`${name}[year]`" v-model="element.value.year" class="form-input h-8 w-1/4">
    </div>
</template>



<script>
export default {

    props:{
        name: {deafult: null},
        subjectChoices: {required: true, type: Object},
        element: {required: true, type: Object},
        index: {required: true, type: Number}
    },
    data() {
        return {
            typedSubjects: [],
            isInputSubjectVisible: false
        }
    },
    methods: {
        toggleSubject(selectedSubject) {
            if(selectedSubject == -1)
                this.isInputSubjectVisible = true;
            else
                this.isInputSubjectVisible = false;

            console.log(selectedSubject);
        }
    },
    created() {
        console.log(this.subjectChoices);
    },
}
</script>
