@props([
    'class',
    'name',
    'inputName',
    'otherValue' => 'other',
    'inputClass',
    'placeholder' => 'Please Specify',
    'value',
])
<div x-data="{
        otherValue: {{ json_encode($otherValue) }},
        value: {{ $value === '' ? json_encode($otherValue) : json_encode($value) }},
    }"
    class="{{ $class }}">
    <select name="{{ $name }}" class="{{ $selectClass }}"
        x-model="value">
        {{ $slot }}
        <option value="{{ $otherValue }}">Other</option>
    </select>
    <template x-if="value == otherValue">
        <input type="text" name="{{ $inputName }}"
            class="{{ $inputClass }}" placeholder="{{ $placeholder }}">
    </template>
</div>
