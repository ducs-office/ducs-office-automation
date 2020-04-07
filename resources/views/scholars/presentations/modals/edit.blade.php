<v-modal name="{{ $modalName }}" height="auto">
    <template v-slot="{ data }">
        <form action="route('scholars.profile.presentation.update' , data('presentation', ''))" method="post" class="p-6">
            @csrf_token
            <h2 class="mr-6">Update Presentation</h2>
            @method('PATCH')
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="city" class="form-label block mb-1">
                        City <span class="text-red-600">*</span>
                    </label>
                    <input type="text" :value="data('presentation.city')" name="city" 
                        class="form-input w-full">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="country" class="form-label block mb-1">
                        Country 
                    </label>
                    <input type="text" :value="data('presentation.country')" name="country" 
                        class="form-input w-full">
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/2">
                    <label for="date" class="form-label block mb-1">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input type="date" :value="data('presentation.date')" name="date" 
                        class="form-input w-full">
                </div>
                <div class="ml-4 w-1/2">
                    <label for="venue" class="block form-label flex-1">Venue:</label>
                    <select id="venue" name="venue" class="block form-input w-full flex-1" :value="data('presenataion.venue')">
                        <option value="" selected> Choose the venue</option>
                        @foreach ($venues as $acronym => $venue)
                        <option value=" {{ $acronym }}"
                            :selected="{{ data('presentation.venue') === $acronym }}">
                            {{ $venue }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="scopus_indexed" class="mr-2" :value="data('presentation.scopus_indexed')">
                <label for="scopus_indexed" class="w-full form-label">
                   Scopus Indexed ?
                </label>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta">Update</button>
            </div>
        </form>
    </template>
</v-modal>