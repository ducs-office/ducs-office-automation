<template>
    <label :for="id">
        <input :id="id" type="file" 
            @input="fileSelected"
            :name="name" 
            :accept="accept" style="height: 0; width: 0;">
        <slot :label="label"></slot>
    </label>
</template>
<script>
export default {
    props: {
        id: {default: ''},
        accept: {default: '*'},
        name: {required: true},
        placeholder: {default: 'No file selected!'}
    },
    data() {
        return {
            file: null
        }
    },
    methods: {
        fileSelected(evt) {
            this.file = evt.target.files[0];
        }
    },
    computed: {
        label() {
            return this.file ? this.file.name : this.placeholder;
        }
    }
}
</script>