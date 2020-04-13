<v-modal name="edit-programme-modal" height="auto">
    <template v-slot="{data}">
        <div class="py-6">
            <h2 class="page-header px-6">Update Programme</h2>
            <form :action="route('staff.programmes.update', data('programme',''))" method="POST" class="px-6">
                @csrf_token @method('PATCH')
                <div class="mb-2">
                    <label for="programme_code" class="w-full form-label mb-1">Code<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_code" type="text" name="code" class="w-full form-input" :value="data('programme.code')">
                </div>
                <div class="mb-2">
                    <label for="programme_wef" class="w-full form-label mb-1">Date (w.e.f)<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_wef" type="date" name="wef" class="w-full form-input" :value="data('programme.wef')">
                </div>
                <div class="mb-2">
                    <label for="programme_name" class="w-full form-label mb-1">Name<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <input id="programme_name" type="text" name="name" class="w-full form-input" :value="data('programme.name')">
                </div>
                <div class="mb-2">
                    <label for="programme_type" class="w-full form-label mb-1">Type<span
                            class="h-current text-red-500 text-lg">*</span></label>
                    <select class="w-full form-input" name="type" required :value="data('programme.type')">
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-magenta">Update</button>
                </div>
            </form>
        </div>
    </template>
</v-modal>



