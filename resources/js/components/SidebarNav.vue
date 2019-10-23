<template>
    <span>
        <slot :isvisible="isVisible" :closesidebarnav="closeSidebarNav"></slot>
    </span>
</template>

<script>
export default {
    data() {
        return {
            isVisible: screen.width <= 640 ? false : true,
        };
    },
    
    created() {
        Events.$on('openSidebarNav', () => {
            this.isVisible = true;
        });
    },

    methods: {
        closeSidebarNav() {
            this.isVisible = false;
        }
    }
}
</script>