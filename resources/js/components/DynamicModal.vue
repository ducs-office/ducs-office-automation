<template>
    <modal v-bind="$attrs" @before-open="beforeOpen">
        <slot :data="getData">
        </slot>
    </modal>
</template>
<script>
export default {
    data() {
        return {
            modalData: {}
        }
    },
    methods: {
        getData(key, defaultValue = null) {
            return key.split('.').reduce(
                (object, key) => object != null ? object[key] : null,
                this.modalData
            ) || defaultValue;
        },
        beforeOpen(event) {
            this.modalData = event.params;
        },
    }
}
</script>
