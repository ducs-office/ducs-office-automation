<div x-data="addRemoveElement({{ json_encode($attributes->get('existing-elements')) }})"
    {{ $attributes->except('existing-elements')}}>
    {{ $title }}
    <button class="link mb-1 ml-auto" x-on:click.prevent="addElement">Add more...</button>
    <template x-for="(element, index) in elements" :key="element">
        <div class="flex mb-1">
            {{ $slot }}
            <button x-on:click.prevent="removeElement(index)" class="btn is-md ml-2 text-red-600">x</button>
        </div>
    </template>
</div>
