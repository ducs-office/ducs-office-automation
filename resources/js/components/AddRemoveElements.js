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
                value: this.createNewElement
            });
        },
        removeElement(index) {
            this.elements.splice(index, 1);
        },
        createNewElement() {
            const el = JSON.parse(JSON.stringify(this.newElement));
            Object.keys(el).map((key) => {
                el[key] = '';
            });
            return el;
        }
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
                Object.keys(el).map((key) => {
                    el[key] = '';
                });
                this.newElement = el;
            }
            else    
                this.newElement = "";
        }
    }
}

