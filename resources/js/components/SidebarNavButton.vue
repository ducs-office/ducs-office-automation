<template>
    <button @click="toggleSidebarNav">
        <slot>
            <feather-icon name="menu" class="h-current" stroke-width="3">Toggle Menu</feather-icon>
        </slot>
    </button>
</template>
<script>
export default {
    methods: {
        toggleSidebarNav() {
            Events.$emit('toggleSidebarNav');
        }
    }
}
</script>
