<template>
    <label :for="id">
        <input v-bind="$attrs" :id="id" type="file"
            @input="fileSelected"
            :name="name"
            accept="image/*"
            class="hidden">
        <slot :imageUrl="imageUrl"></slot>
    </label>
</template>
<script>
export default {
    props: {
        id: {default: ''},
        name: {required: true},
        placeholder: {default: 'No file selected!'},
        placeholderSrc: {default: 'https://plachehold.it/200'}
    },
    data() {
        return {
            imageFile: null,
            imageUrl: this.placeholderSrc,
        }
    },
    methods: {
        fileSelected(evt) {
            this.imageFile = evt.target.files[0];

            const reader = new FileReader();
            reader.addEventListener('load', () => this.imageUrl = reader.result);
            reader.readAsDataURL(this.imageFile);
        }
    },
}
</script>
