<template>
    <div>
        <select :name="name" :class="selectClass" @change="onChange">
            <slot></slot>
            <option :value="otherValue">Other</option>
        </select>
        <input type="text" v-if="showOtherInput" :name="otherName"
        :class="inputClass"
        placeholder="placeholder" class="form-input mt-2 h-8 w-full">
    </div>
</template>

<script>
export default {
    props: {
        name: {required: true},
        otherName: {required: true},
        otherValue: {required: true},
        selectClass: {default: ''},
        inputClass: {default: ''},
        placeholder: {required: true},
    },
    data() {
        return {
            showOtherInput: false,
        }
    },
    methods: {
        onChange(e) {
            if(e.target.value == this.otherValue)
                this.showOtherInput = true;
            else
                this.showOtherInput = false;
        }
    },
}
</script>
