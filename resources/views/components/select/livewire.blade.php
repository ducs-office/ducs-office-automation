@props([
    'name' => '',
    'placeholder' => 'Select an item',
    'searchPlaceholder' => 'Search an item',
    'queryModel' => 'searchQuery',
])
<div x-data="livewireSelect()" x-init="init()"
    {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <button type="button"
        class="text-left form-select w-full"
        x-on:click.prevent="toggle()">
        <span x-show="value != ''" x-html="label"></span>
        <span x-show="value == ''" class="opacity-50">{{ $placeholder }}</span>
    </button>
    <input type="hidden" name="{{ $name }}" x-model="value">
    <div x-show="opened" x-on:click.away="close()"
        class="absolute z-20 page-card border shadow-lg rounded p-4 w-full inset-x-0 mt-1">
        <input x-ref="input" type="text"
            placeholder="{{ $searchPlaceholder }}"
            class="w-full form-input mb-4"
            wire:model="{{ $queryModel }}"
            x-on:input.stop
            x-on:keydown.arrow-down="next()"
            x-on:keydown.arrow-up="prev()"
            x-on:keydown.enter.prevent="selectHighlighted()"
            x-on:keydown.escape.prevent="close()">
        <div x-ref="dom" wire:ignore class="-mx-4 max-h-48 overflow-y-auto"></div>
        <div x-ref="options" x-show="false">
            {{ $slot }}
        </div>
    </div>
</div>
@push('scripts')
<script>
    const livewireSelect = ({
        highlightClasses = 'bg-gray-200',
    } = {}) => ({
        value: '',
        label: null,
        size: 0,
        options: [],
        opened: false,
        toggle() {
            this.opened ? this.close() : this.open();
        },
        open() {
            this.opened = true;
            this.$nextTick(() => this.$refs.input.focus());
        },
        close() {
            this.opened = false;
        },
        highlighted: -1,
        next() { this.highlight((this.highlighted + 1) % this.size) },
        prev() { this.highlight(this.highlighted == 0 ? this.size - 1 : this.highlighted-1) },
        highlight(index) { this.highlighted = index },
        selectHighlighted() {
            this.select(this.highlighted);
        },
        select(index) {
            if (index < 0 || index >= this.size) {
                return;
            }

            this.selectByOption(this.options[index]);
        },
        selectByOption(optionEl) {
            const valueAttr = optionEl.attributes.value;
            const labelAttr = optionEl.attributes.label;
            console.log(valueAttr);
            if(valueAttr) {
                this.value = valueAttr.nodeValue;
                this.label = labelAttr ? labelAttr.nodeValue : optionEl.innerHTML;
                this.$el.dispatchEvent(new CustomEvent('input', {
                    detail: {value: this.value}
                }));
            }
            this.close();
        },
        isHighlighted(index) { return this.highlighted == index },
        onHighlight(index) {
            for (let i in this.options) {
                this.options[i].classList.remove(highlightClasses);
            }
            this.options[index].classList.add(highlightClasses);
            this.options[index].scrollIntoView({block: 'nearest'});
        },
        registerOptions() {
            this.$refs.dom.innerHTML = this.$refs.options.innerHTML;
            this.size = this.$refs.dom.children.length;
            this.options = Array.from(this.$refs.dom.children).map((optionEl, index) => {
                optionEl.addEventListener('mouseover', () => this.highlight(index));
                optionEl.addEventListener('click', (e) => this.selectByOption(optionEl));
                return optionEl;
            });
        },
        selectInitialValue() {
            const value = this.$el.attributes.value
                ? this.$el.attributes.value.nodeValue
                : this.$el.value;

            const index = this.options.findIndex(
                option => option.attributes.value.nodeValue == value
            );

            this.select(index);
        },
        init() {
            this.registerOptions();
            const observer = new MutationObserver((mutationsList, observer) => {
                if(mutationsList.length > 0) {
                    this.registerOptions();
                }
            });
            observer.observe(this.$refs.options, { childList: true });

            this.selectInitialValue();
            this.$watch('highlighted', this.onHighlight.bind(this));
        },
    });
</script>
@endpush
