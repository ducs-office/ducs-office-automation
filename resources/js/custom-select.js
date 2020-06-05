const addMultipleSelectFeature = () => ({
    value: [],
    isEmpty() { return this.value == null || this.value.length === 0 },
    isSelected(value) { return this.value.includes(value) },
    select(...values) { this.value = [...this.value, ...values] },
    deselect(index) {
        if (index < 0 ) { return }

        this.value.splice(index, 1);
    },
    deselectLast() { this.deselect(this.value.length - 1) },
    deselectByValue(value) { this.deselect(this.value.indexOf(value)) },
    toggleChoice(value) { this.isSelected(value) ? this.deselectByValue(value) : this.select(value) },
    toggleHighlighted() { this.toggleChoice(this.choices[this.highlighted][this.valueKey]) },
    selectedChoices() { return this.value.map(val => this.choices.find(choice => val == choice[this.valueKey])) }
});

const addSingleSelectFeature = () => ({
    value: null,
    isEmpty() { return this.value == '' || this.value == null },
    isSelected(value) { return this.value == value },
    selectHighlighted() { this.select(this.highlighted) },
    select(value) { this.value = value },
    selectedChoice() { this.choices.find(choice => this.value == choice[this.valueKey]) }
});

export default config => ({
    init() {
        this.choicesCount = this.$refs[this.choicesRef].children.length;

        this.$watch("highlighted", () => {
            this.$refs[this.choicesRef]
                .children[this.highlighted]
                .scrollIntoView({ block: "nearest" });
        });

        this.$watch("value", (value) => {
            this.$refs[this.choicesRef].value = value;
            this.$refs[this.choicesRef].dispatchEvent(new Event("input", {
                bubbles: true,
                cancelable: true,
            }));
        });

        if(this.multiple && this.value == null) {
            this.value = [];
        }

        this.$refs[this.choicesRef].value = this.value;
    },
    open: false,
    multiple: false,
    valueKey: "value",
    choices: [],
    choicesRef: "choices",
    highlighted: 0,
    isHighlighted(index) { return index == this.highlighted },
    highlightNext() { this.highlight((this.highlighted + 1) % this.choicesCount) },
    highlightPrev() { this.highlight(this.highlighted == 0 ? this.choicesCount - 1 : this.highlighted - 1) },
    highlight(index) {
        if (index < 0 || index >= this.choicesCount) {
            return;
        }
        this.highlighted = index;
    },
    ...(config.multiple || false
        ? addMultipleSelectFeature()
        : addSingleSelectFeature()),
    onButtonClick() {
        if (this.open) return;

        this.open = true;
        this.$nextTick(() => {
            this.$refs[this.choicesRef].focus();
            this.$refs[this.choicesRef].children[this.highlighted].scrollIntoView({ block: 'nearest' })
        });
    },
    onOptionSelected() {
        if(! this.open) {
            return (this.open = true);
        }

        return this.multiple ? this.toggleHighlighted() : this.selectHighlighted();
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
