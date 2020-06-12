@props([
    'class',
    'name',
    'inputName',
    'otherValue' => '',
    'inputClass',
    'placeholder' => 'Please Specify',
    'value',
])
<div x-data="initialise( {{ json_encode($otherValue) }} )"  class="{{ $class }}">
    <select name="{{ $name }}" class="{{ $selectClass }}"
        x-on:change="$form.showInputIfOtherSelected($event)"
        x-model="{{ $value }}">
        {{ $slot }}
        <option value=""> Other </option>
    </select>
    <template x-if="$form.showInput">
        <input type="text" name="{{ $inputName }}" 
            class="{{ $inputClass }}">
    </template>
</div>
@push('scripts')
<script>
    function initialise(otherValue) {
        return {
            $form: {
                otherValue: otherValue,
                showInput: false,
                showInputIfOtherSelected(e) {
                    if (e.target.value == this.otherValue)
                        this.showInput = true;
                    else
                        this.showInput = false;
                }
            }
        }
    }
</script>
@endpush