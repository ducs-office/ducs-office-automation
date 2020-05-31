@props([
    'count' => 0,
    'items' => [],
    'max' => 10,
    'newObject' => '',
])
<div x-data="addRemoveElement(
    {{ $count }},
    {{ $max }},
    () => ({{ json_encode($newObject) }}))"
    x-init="initialise({{ json_encode($items) }})"
    {{ $attributes }}>
    {{ $slot }}
</div>
