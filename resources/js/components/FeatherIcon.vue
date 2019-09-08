<template>
    <svg viewBox="0 0 24 24" 
        :stroke-width="strokeWidth"
        fill="none"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round">
        <title><slot></slot></title>
        <g v-html="icon"></g>
    </svg>
</template>
<script>
import feather from 'feather-icons';
export default {
    props: {
        name: {required: true},
        strokeWidth: {default: 2},
    },
    computed: {
        icon() {
            return feather.icons.hasOwnProperty(this.name || 'x') 
                ? feather.icons[this.name].contents
                : feather.icons['x'].contents;
        }
    }
}
</script>
