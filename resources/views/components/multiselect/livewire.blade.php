@props([
    'name' => '',
    'placeholder' => 'Select multiple Items',
    'searchPlaceholder' => 'Search an Items',
    'queryModel' => 'searchQuery',
])
<div x-data="livewireMultiselect()" x-init="init()" {{ $attributes->merge(['class' => 'relative w-full']) }}>
    <div class="text-left form-input w-full flex flex-wrap -m-1"
        x-on:click.prevent="open()">
        <template x-for="(label, index) in labels">
            <div class="m-1 px-2 py-1 text-sm bg-magenta-700 text-white rounded inline-flex items-center">
                <span x-html="label" class="mr-1"></span>
                <button type="button" x-on:click.prevent.stop="deleteByIndex(index)">
                    <x-feather-icon name="x" class="h-current"></x-feather-icon>
                </button>
            </div>
        </template>
        <input x-ref="input" type="text"
            class="ml-1 flex-1"
            placeholder="{{ $placeholder }}"
            wire:model="{{ $queryModel }}"
            x-on:focus="open()"
            x-on:input.stop
            x-on:keydown.arrow-down="next()"
            x-on:keydown.arrow-up="prev()"
            x-on:keydown.enter.prevent="selectHighlighted()"
            x-on:keydown.backspace="onBackspace($event)"
            x-on:keydown.escape.prevent="close()">
    </div>
    <template x-for="value in values">
        <input type="hidden" name="{{ $name }}" x-model="value">
    </template>
    <div x-show="opened" x-on:click.away="close()" class="absolute z-20 page-card border shadow-lg rounded p-4 w-full inset-x-0 mt-1">
        <div x-ref="dom" wire:ignore class="-mx-4 max-h-48 overflow-y-auto"></div>
        <div x-ref="options" x-show="false">
            {{ $slot }}
        </div>
    </div>
</div>
@push('scripts')
<script>
    const livewireMultiselect = ({
        highlightClasses = 'bg-gray-200',
    } = {}) => ({
        values: [],
        labels: [],
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
                this.values.push(valueAttr.nodeValue);
                this.labels.push(labelAttr ? labelAttr.nodeValue : optionEl.innerHTML);
                this.$el.dispatchEvent(new CustomEvent('input', {
                    detail: {value: this.values}
                }));
            }
        },
        deleteLast() {
            this.deleteByIndex(this.values.length - 1);
        },
        deleteByIndex(index) {
            if (index < 0 || index >= this.values) {
                return;
            }

            this.values.splice(index, 1);
            this.labels.splice(index, 1);
            this.$el.dispatchEvent(new CustomEvent('input', {
                detail: {value: this.values}
            }));
        },
        onBackspace(event) {
            if(event.target.value == '') {
                this.deleteLast();
            }
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
        selectInitialValue()
        {
            const values = this.$el.attributes.value
                ? this.$el.attributes.value.nodeValue
                : this.$el.value;

            for(let value of values) {
                const index = this.options.findIndex(
                    option => option.attributes.value.nodeValue == value
                );

                this.select(index);
            }
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
