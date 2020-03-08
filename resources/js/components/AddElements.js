export default {
    props: {
        tag: {default: 'div'},
    },
    render(h) {
        return h(
            this.tag,
            this.$scopedSlots.default({
                elements: this.elements,
                addElement: this.addElement,
                removeElement: this.removeElement,
            })
        );
    },
    data() {
        return {
            elements: [],
        }
    },
    methods: {
        addElement() {
            this.elements.push({
                stub: ''
            });
        },
        removeElement(index) {
            console.log(index);
            this.elements.splice(index, 1);
        }
    }
}

