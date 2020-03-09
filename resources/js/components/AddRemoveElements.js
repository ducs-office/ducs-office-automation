export default {
    props: {
        tag: {default: 'div'},
        existingElements: {default: null, type: Array},
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
                value: ''
            });
        },
        removeElement(index) {
            this.elements.splice(index, 1);
        },
    },
    created() {
        if(this.existingElements !== null) {
            this.existingElements.forEach($el => { 
                this.elements.push({
                    value: $el
                })
            });
        }
        else this.addElement();
    },
}

