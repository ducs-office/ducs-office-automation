@props([
    'id' => '',
    'accept' => 'image/*',
    'name' => '',
    'imageSrc' => 'https://plachehold.it/250',
])

<label for="{{ $id }}" {{ $attributes }}
    x-data="{
        src: '{{ $imageSrc }}',
        alt: 'file upload placeholder image',
        fileSelected: function(file) {
            this.alt = file.name;
            const reader = new FileReader();
            reader.addEventListener('load', () => this.src = reader.result);
            reader.readAsDataURL(file);
        }
    }">
    <input id="{{ $id }}" type="file" style="display: none;"
        name="{{ $name }}"
        accept="{{ $accept }}"
        x-on:input="fileSelected($event.target.files[0])">
    {{ $slot }}
</label>

{{-- USAGE: --}}
{{-- <x-input.image id="avatar name="avatar"
    image-src="{{ $user->avatar_url }}"
    class="style the container">
    <img x-bind:src="src" class="depends on you" x-bind:alt="alt">
</x-input.image> --}}
