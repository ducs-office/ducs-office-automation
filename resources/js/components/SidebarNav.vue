<script>
export default {
    data() {
        return {
            is_visible: screen.width <= 640 ? false : true,
        };
    },
    created() {
        const app = this;
        Events.$on('openSidebarNav', function() {
            app.is_visible = true;
        });
    }
}
</script>