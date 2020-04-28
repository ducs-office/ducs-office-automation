@php
list($permanentMembers, $addedMembers) = collect($scholar->advisory_committee)
    ->partition(function($member) {
        return in_array($member->type, ['supervisor', 'cosupervisor']);
    });
@endphp
<v-modal name="{{ $modalName }}" height="auto" :scrollable="true">
    <template v-slot="{ data }">
    <div class="p-6">
        <h2 class="text-lg font-bold mb-8">@{{ data('actionName') }} Scholar's Advisory Committee</h2>
        <form :action="data('actionUrl', '')" method="POST">
            @csrf_token @method('PATCH')
            @foreach($permanentMembers as $member)
                <div class="mb-3">
                    <label for="name" class="w-full form-label mb-1">
                        {{ Str::title($member->type) }}
                    </label>
                    <input type="text" class="w-full form-input" disabled
                        value="{{ $member->name }}">
                </div>
            @endforeach
            <add-remove-elements :existing-elements="{{ json_encode(old('committee', $addedMembers->map->toArray()->values()->all())) }}">
                <template v-slot="{ elements, addElement, removeElement }">
                    <advisory-committee-member v-for="(data, index) in elements" :data-member="data"
                        :type-name="`committee[${index}][type]`"
                        :key="data.type"
                        class="border rounded p-2 mb-3">
                        <template v-slot:faculty="{ member }">
                            <label :for="`faculty.${index}`" class="w-full form-label">Faculty</label>
                            <select :id="`faculty.${index}`" :name="`committee[${index}][id]`" class="w-full form-input" v-model="member.id">
                                <option :value="null" disabled>-- Select a Faculty Teacher --</option>
                                @foreach($faculty as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </template>
                        <template v-slot:external="{ member }">
                            <div class="mb-2">
                                <label :for="`committe.${index}.name`" class="w-full form-label">Name</label>
                                <input :id="`committe.${index}.name`" :name="`committee[${index}][name]`" type="text" class="w-full form-input" placeholder="e.g. John Doe"
                                    v-model="member.name" required>
                            </div>
                            <div class="flex mb-2 -mx-2">
                                <div class="mx-2 flex-1">
                                    <label :for="`committe.${index}.designation`" class="w-full form-label">Designation</label>
                                    <input :id="`committe.${index}.designation`" :name="`committee[${index}][designation]`" type="text" class="w-full form-input"
                                        placeholder="e.g. Head of Department" v-model="member.designation" required>
                                </div>
                                <div class="mx-2 flex-1">
                                    <label :for="`committe.${index}.affiliation`" class="w-full form-label">Affiliation</label>
                                    <input :id="`committe.${index}.affiliation`" :name="`committee[${index}][affiliation]`" type="text" class="w-full form-input"
                                        placeholder="e.g. Department of Mathematics" v-model="member.affiliation" required>
                                </div>
                            </div>
                            <div class="flex mb-2 -mx-2">
                                <div class="mx-2 flex-1">
                                    <label :for="`committe.${index}.email`" class="w-full form-label">Email</label>
                                    <input :id="`committe.${index}.email`" :name="`committee[${index}][email]`" type="email" class="w-full form-input"
                                        placeholder="e.g. johndoe@gmail.com" v-model="member.email" required>
                                </div>
                                <div class="mx-2 flex-1">
                                    <label :for="`committe.${index}.phone`" class="w-full form-label">Phone</label>
                                    <input :id="`committe.${index}.phone`" :name="`committee[${index}][phone]`" type="text" class="w-full form-input"
                                        placeholder="e.g. johndoe@gmail.com" v-model="member.phone">
                                </div>
                            </div>
                        </template>
                        <div>
                            <button class="ml-2 btn btn-magenta" @click.prevent="removeElement(index)">Remove</button>
                        </div>
                    </advisory-committee-member>
                    <button class="link" @click.prevent="addElement">Add more...</button>
                    <div class="mt-5">
                        <button type="submit" class="btn btn-magenta">@{{ data('actionName') }}</button>
                    </div>
                </template>
            </add-remove-elements>
        </form>
    </div>
    </template>
</v-modal>
