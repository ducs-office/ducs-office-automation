<v-modal name="{{ $modalName }}" height="auto">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">New Programme</h2>
        <form action="{{ route('programmes.store') }}" method="POST" class="flex items-end">
            @csrf_token
            <div class="flex-1 mr-2">
                <label for="programme_code" class="w-full form-label">Programme Code<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_code" type="text" name="code" class="w-full form-input">
            </div>
            <div class="flex-1 mr-5">
                <label for="programme_name" class="w-full form-label">Date (w.e.f)<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_name" type="date" name="wef" class="w-full form-input">
            </div>
            <div class="flex-1 mr-5">
                <label for="programme_name" class="w-full form-label">Programme<span
                        class="h-current text-red-500 text-lg">*</span></label>
                <input id="programme_name" type="text" name="name" class="w-full form-input">
            </div>
            <div>
                <button type="submit" class="btn btn-magenta">Create</button>
            </div>
        </form>
    </div>
</v-modal>
