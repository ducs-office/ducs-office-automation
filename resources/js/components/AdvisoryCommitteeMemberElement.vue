<template>
    <fieldset>
        <legend class="px-2">Advisory Committee Member</legend>
        <div>
            <label class="w-full form-label">Type</label>
            <div class="flex">
                <select class="w-full form-input" v-model="member.type" :name="typeName">
                    <option v-for="(type, value) in types" :key="value" :value="value" v-text="type"></option>
                </select>
                <slot></slot>
            </div>
        </div>
        <div v-if="member.type=='faculty_teacher'">
            <slot name="faculty" :member="member"></slot>
        </div>
        <slot v-else name="external" :member="member"></slot>
    </fieldset>
</template>
<script>
const defaultMember = {
    type: 'faculty_teacher',
    id: null,
    name: null,
    designation: null,
    affiliation: null,
    email: null,
    phone: null,
}
export default {
    props: {
        typeName: { default: null },
        dataMember: { default: () => defaultMember }
    },
    data() {
        return {
            types: {
                faculty_teacher: 'Faculty Teacher',
                external: 'External'
            },
            member: this.dataMember,
        };
    },
    created() {
        if (!Object.keys(this.types).includes(this.member.type)) {
            this.member.type = 'faculty_teacher';
            this.member.id = null;
        }
    }

}
</script>
