<div x-data="{
    selected: {{ json_encode($selectedMap()) }},
    viewPermissionId: {{ json_encode(optional($getViewPermission())->id) }},
    onChange(id, value) {
        if (value == true) {
            return this.selected[this.viewPermissionId] = value;
        }

        if (id == this.viewPermissionId) {
            return Object.keys(this.selected).forEach(id => this.selected[id] = false);
        }

        if (id != this.viewPermissionId && ! Object.values(this.selected).some(i => i)) {
            return this.selected[this.viewPermissionId] = false;
        }
    }
}"
{{ $attributes }}>
    @foreach ($permissions as $index => $permission)
    <label for="permission-{{ $permission->id }}"
        class="px-2 py-1 border rounded inline-flex space-x-2 items-center">
        <input id="permission-{{ $permission->id }}"
            x-on:change="onChange('{{ $permission->id }}', $event.target.checked)"
            x-model="selected['{{ $permission->id }}']"
            type="checkbox"
            name="{{ $fieldName }}"
            class="form-checkbox"
            value="{{ $permission->id }}">
        <span>{{ $getAction($permission) }}</span>
    </label>
    @endforeach
</div>
