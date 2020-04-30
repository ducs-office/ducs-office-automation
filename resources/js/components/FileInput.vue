<template>
    <label :for="id" tabindex="0">
        <input tabindex="-1" v-bind="$attrs" :id="id" type="file"
            @input="fileSelected"
            :name="name"
            :accept="accept" style="position:absolute; height: 0; width: 0;">
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
            files: []
        }
    },
    methods: {
        fileSelected(evt) {
            this.files = Array.from(evt.target.files);
        }
    },
    computed: {
        label() {
            return this.files.length ? this.files.map(file => file.name).join(', ') : this.placeholder;
        }
    }
}
</script>
