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
            newElement: {},
        }
    },
    methods: {
        addElement() {
            this.elements.push({
                value: this.newElement
            });
        },
        removeElement(index) {
            this.elements.splice(index, 1);
        },
    },
    created() {
        if(this.existingElements.length ) {
            this.existingElements.forEach(el => { 
                this.elements.push({
                    value: el
                })
            });

            const el = JSON.parse(JSON.stringify(this.existingElements[0]));

            if(typeof(this.existingElements[0]) == "object") {
                Object.keys(el).map((type) => {
                    el[type] = '';
                });
                this.newElement = el;
            }
            else    
                this.newElement = "";
        }
    },
}

