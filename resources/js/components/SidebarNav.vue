<script>
export default {
    data() {
        return {
            isVisible: screen.width <= 640 ? false : true,
        };
    },

    created() {
        Events.$on('toggleSidebarNav', this.toggleSidebarNav);
    },

    methods: {
        toggleSidebarNav() {
            this.isVisible = ! this.isVisible;
        }
    }
}
</script>
