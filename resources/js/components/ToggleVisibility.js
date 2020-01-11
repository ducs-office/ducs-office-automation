export default {
    props: {
        tag: {default: 'div'},
        shown: {default: false}
    },
    render(h) {
        return h(
            this.tag,
            this.$scopedSlots.default({
                isVisible: this.isVisible,
                show: this.show,
                hide: this.hide,
                toggle: this.toggle
            })
        );
    },
    data() {
        return {
            isVisible: this.shown
        }
    },
    methods: {
        show() {
            this.isVisible = true;
        },
        hide() {
            this.isVisible = false;
        },
        toggle() {
            this.isVisible = ! this.isVisible;
        }
    }
}
