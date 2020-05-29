const addVisibiltyFeature = (initialState = false) => ({
    opened: initialState,
    toggle() {
        this.opened ? this.close() : this.open();
    },
    open() {
        if (! this.opened) {
            this.opened = true;
            this.onOpen();
        }
    },
    close() {
        if (this.opened) {
            this.opened = false;
            this.onClose();
        }
    },
    onOpen(){},
    onClose(){},
});

const addHighlightingFeature = () => ({
    options: [], // duplication, so that it doesnt break without add addRegisterOptionsFeature
    highlighted: -1,
    isHighlighted(index) {
        return this.highlighted == index;
    },
    highlightNext() {
        this.highlight((this.highlighted + 1) % this.options.length);
    },
    highlightPrev() {
        this.highlight(
            this.highlighted == 0 ? this.options.length - 1 : this.highlighted - 1
        );
    },
    highlight(index) {
        this.highlighted = index;
        this.onHighlighted(index);
    },
    onHighlighted(index) {}
});

const addMultipleSelectFeature = () => ({
    options: [],
    selectedOptionHTMLs: [],
    selectedValue: [],
    isSelected(value) {
        return this.selectedValue.includes(value);
    },
    select(...values) {
        for (let value of values) {
            this.selectedValue.push(value);
        }

        this.options
            .filter(option => values.includes(option.value))
            .forEach(option => this.selectedOptionHTMLs.push(option.innerHTML));

        this.onChanged(this.selectedValue);
    },
    deselect(index) {
        if (index < 0 || index >= this.selectedValue.length) {
            return;
        }

        this.selectedValue.splice(index, 1);
        this.selectedOptionHTMLs.splice(index, 1);
        this.onChanged(this.selectedValue);
    },
    deselectLast() {
        this.deselect(this.selectedValue.length - 1);
    },
    deselectByValue(value) {
        if (this.isSelected(value)) {
            this.deselect(this.selectedValue.indexOf(value));
        }
    },
    toggleValue(value) {
        if (this.selectedValue.includes(value)) {
            this.deselectByValue(value);
        } else {
            this.select(value);
        }
    },
    toggleHighlighted() {
        this.toggleValue(this.options[this.highlighted].value);
    },
    isEmpty() {
        return this.selectedValue == null || this.selectedValue.length === 0;
    },
    onChanged(value) {}
});

const addSingleSelectFeature = () => ({
    options: [],
    selectedValue: '',
    selectedOptionHTML: '',
    isSelected(value) {
        return this.selectedValue == value;
    },
    select(value) {
        this.selectedValue = value;

        const index = this.options.findIndex(option => option.value == value);
        if(index > -1 && index < this.options.length) {
            this.selectedOptionHTML = this.options[index].innerHTML;
        }

        this.onChanged(value);
    },
    isEmpty() {
        return this.selectedValue == '' || this.selectedValue == null;
    },
    onChanged(value) {},
});

const addRegisterOptionsFeature = (multiple = false) => ({
    options: [],
    initializeOptions(optionHTMLs) {
        this.options = optionHTMLs.map(this.registerOption.bind(this));
    },
    registerOption(optionHTML, index) {
        const optionTemplate = document.createElement('template');
        optionTemplate.innerHTML = optionHTML.trim();
        const optionEl = optionTemplate.content.firstChild;

        optionEl.value = optionEl.attributes.value
            ? optionEl.attributes.value.nodeValue
            : null;

        optionEl.addEventListener('mouseover', e => this.highlight(index));
        if(! multiple) {
            optionEl.addEventListener('click', e => this.select(optionEl.value));
        } else {
            optionEl.addEventListener('click', e => this.toggleValue(optionEl.value));
        }

        return optionEl;
    },
})

export default ({
    selectedClasses = 'bg-gray-300',
    highlightClasses = 'bg-magenta-600 text-white',
    multiple = false
} = {}) => ({
    ...addVisibiltyFeature(),
    ...addHighlightingFeature(),
    ...(multiple ? addMultipleSelectFeature() : addSingleSelectFeature()),
    ...addRegisterOptionsFeature(multiple),
    init() {
        const optionsContainer = this.$refs.options;
        const renderContainer = this.$refs.dom;

        const childListObserver = new MutationObserver(mutationsList => {
            if (mutationsList.length == 0) {
                return;
            }

            this.initializeOptions(
                Array.from(optionsContainer.children).map(
                    optionEl => optionEl.outerHTML
                )
            );

            this.options.forEach(option => renderContainer.appendChild(option));
        });
        childListObserver.observe(optionsContainer, { childList: true });

        this.initializeOptions(
            Array.from(optionsContainer.children).map(
                optionEl => optionEl.outerHTML
            )
        );

        this.options.forEach(option =>
            renderContainer.appendChild(option)
        );

        if(multiple) {
            this.selectMultipleInitialValues();
        } else {
            this.selectInitialValue();
        }
    },
    selectMultipleInitialValues() {
        const value = this.$el.attributes.value
            ? this.$el.attributes.value.nodeValue
            : this.$el.value;

        if(value == null) {
            return;
        }

        this.select(
            ...(Array.isArray(value) ? value : [value])
        );
    },
    selectInitialValue() {
        this.select(
            this.$el.attributes.value
                ? this.$el.attributes.value.nodeValue
                : this.$el.value
        );
    },
    selectHighlighted() {
        this.select(this.highlighted);
    },
    onChanged(value) {
        this.$el.value = value;
        this.$el.dispatchEvent(new Event("input", {
            bubbles: true,
            cancelable: true
        }));

        for (let option of this.options) {
            if (this.isSelected(option.value)) {
                selectedClasses.split(' ').forEach(
                    cssClass => option.classList.add(cssClass)
                );
            } else {
                selectedClasses.split(' ').forEach(
                    cssClass => option.classList.remove(cssClass)
                );
            }
        }

        if (!multiple) {
            this.close();
        }
    },
    onHighlighted(index) {
        for (let option of this.options) {
            highlightClasses.split(' ').forEach(
                cssClass => option.classList.remove(cssClass)
            );
        }
        highlightClasses.split(' ').forEach(
            cssClass => this.options[index].classList.add(cssClass)
        );
        this.options[index].scrollIntoView({block: 'nearest'});
    },
    onBackspaceKey(event) {
        if (multiple && event.target.value == "") {
            this.deleteLast();
        }
    }
});
