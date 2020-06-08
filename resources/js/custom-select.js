import { createPopper } from '@popperjs/core';

// local helper
function constrainBounds(current, end, start = 0) {
    return current < start ? end - (start - current) : current % end;
}

const addMultipleSelectFeature = () => ({
    value: [],
    isEmpty() { return this.value == null || this.value.length === 0 },
    isSelected(value) { return this.value.find(val => value == val) },
    select(...values) { this.value = [...this.value, ...values] },
    deselect(index) {
        if (index < 0 ) { return }

        this.value = [
            ...this.value.slice(0, index),
            ...this.value.slice(index + 1),
        ];
    },
    deselectLast() { this.deselect(this.value.length - 1) },
    deselectByValue(value) { this.deselect(this.value.indexOf(value)) },
    toggleChoice(value) { this.isSelected(value) ? this.deselectByValue(value) : this.select(value) },
    toggleHighlighted() {
        if (this.highlighted < 0 || this.highlighted >= this.choices.length) return;

        this.toggleChoice(this.choices[this.highlighted][this.valueKey]);
    },
});

const addSingleSelectFeature = () => ({
    value: null,
    isEmpty() { return this.value == '' || this.value == null },
    isSelected(value) { return this.value == value },
    selectHighlighted() {
        if (this.highlighted < 0 || this.highlighted >= this.choices.length) return;
        this.select(this.choices[this.highlighted][this.valueKey]);
    },
    select(value) { this.value = value },
});

export default config => ({
    getSelectedChoices() {
        if (this.value == null ) return [];
        return (this.multiple ? this.value : [this.value])
            .map(val => this.choices.find(choice => val == choice[this.valueKey]))
            .filter(option => option != null)
    },
    init() {
        this.$watch("highlighted", () => this.scrollToHighlighted());

        this.$watch("value", (value) => {
            this.$refs[this.choicesRef].value = value;
            this.selectedChoices = this.getSelectedChoices();
            this.$refs[this.choicesRef].dispatchEvent(new Event("input", {
                bubbles: true,
                cancelable: true,
            }));
        });

        if(this.multiple && this.value == null) {
            this.value = [];
        }

        this.selectedChoices = this.getSelectedChoices();
        this.$refs[this.choicesRef].value = this.value;
        this.highlightNext();
    },
    open: false,
    multiple: false,
    valueKey: "value",
    choices: [],
    selectedChoices: [],
    disabledChoices: [],
    choicesRef: "choices",
    highlighted: -1,
    isDisabled(index) { return this.disabledChoices.includes(index) },
    jumpDisabled(index, direction = 1) {
        index = constrainBounds(index + direction, this.choices.length);
        const startIndex = index;

        do {
            if (!this.isDisabled(index)) {
                return index;
            }
            index = constrainBounds(index + direction, this.choices.length);
        } while (index != startIndex);

        return -1;
    },
    isHighlighted(index) { return index == this.highlighted },
    highlightNext() {
        const index = this.jumpDisabled(this.highlighted, 1);
        this.highlight(index);
    },
    highlightPrev() {
        const index = this.jumpDisabled(this.highlighted, -1);
        this.highlight(index);
    },
    highlight(index) {
        if (index < 0 || index >= this.choicesCount) {
            return;
        }

        if (this.isDisabled(index)) return;

        this.highlighted = index;
    },
    scrollToHighlighted() {
        if(this.highlighted < 0 || this.highlighted >= this.choices.length) return;

        this.$refs[this.choicesRef]
            .children[this.highlighted]
            .scrollIntoView({ block: "nearest" });
    },
    ...(config.multiple || false
        ? addMultipleSelectFeature()
        : addSingleSelectFeature()),
    onButtonClick() {
        if (this.open) return;

        this.open = true;
        this.$nextTick(() => {
            (this.$refs[this.inputRef] || this.$refs[this.choicesRef]).focus();
            this.scrollToHighlighted();
            createPopper(
                this.$refs.button,
                this.$refs[this.choicesRef].parentNode,
                {
                    placement: "bottom",
                    modifiers: [
                        {
                            name: "offset",
                            options: { offset: [0, 8] }
                        }
                    ]
                }
            );
        });
    },
    onOptionSelected() {
        if(! this.open) {
            return (this.open = true);
        }

        if(! this.multiple) {
            this.selectHighlighted();
            this.open = false;
            this.$refs.button.focus();
        } else {
            this.toggleHighlighted();
        }

    },
    onEscape() {
        this.open = false;
        this.$refs.button.focus();
    },
    onBackspaceKey(event) {
        if (this.multiple && event.target.value == "") {
            this.deleteLast();
        }
    },
    ...config
});
