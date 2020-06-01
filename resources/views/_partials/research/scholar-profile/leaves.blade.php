<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Leaves
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
        @can('create', [Leave::class, $scholar])
        <div class="mt-3 text-right">
            <button class="btn btn-magenta is-sm"
                @click="$modal.show('apply-for-leave-modal')">
                Apply For Leaves
            </button>
            <v-modal name="apply-for-leave-modal" height="auto">
                <template v-slot="{ data }">
                    <form action="{{ route('scholars.leaves.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
                        <h3 class="text-lg font-bold mb-4">Add Leave</h3>
                        @csrf_token
                        <input v-if="data('extensionId')" type="hidden" name="extended_leave_id" :value="data('extensionId')">
                        <div class="flex mb-2">
                            <div class="flex-1 mr-2">
                                <label for="from_date" class="w-full form-label mb-1">
                                    From Date
                                    <span class="text-red-600 font-bold">*</span>
                                </label>
                                <div v-if="data('extension_from_date')" class="w-full form-input cursor-not-allowed bg-gray-400 hover:bg-gray-400">
                                    <span v-text="data('extension_from_date', '')"></span>
                                    <input type="hidden" name="from" :value="data('extension_from_date', '')">
                                </div>
                                <input v-else id="from_date" type="date" name="from"
                                    placeholder="From Date"
                                    class="w-full form-input">
                            </div>
                            <div class="flex-1 ml-2">
                                <label for="to_date" class="w-full form-label mb-1">
                                    To Date
                                    <span class="text-red-600 font-bold">*</span>
                                </label>
                                <input type="date" name="to" id="to_date" placeholder="To Date" class="w-full form-input"
                                    :min="data('extension_from_date', '')">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="reason" class="w-full form-label mb-1">
                                Reason <span class="text-red-600 font-bold">*</span>
                            </label>
                            <select id="leave_reasons" name="reason" class="w-full form-select" onchange="
                                if(reason.value === 'Other') {
                                    reason_text.style = 'display: block;';
                                } else {
                                    reason_text.style = 'display: none;';
                                }">
                                <option value="Maternity/Child Care Leave">Maternity/Child Care Leave</option>
                                <option value="Medical">Medical</option>
                                <option value="Duty Leave">Duty Leave</option>
                                <option value="Deregistration">Deregistration</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" name="reason_text" class="w-full form-input mt-2 hidden" placeholder="Please specify...">
                        </div>
                        <div class="mb-2">
                            <label for="application" class="w-full form-label mb-1">
                                Attach Application
                                <span class="text-red-600 font-bold">*</span>
                            </label>
                            <input id="application" type="file" name="application" class="w-full form-input mt-2" accept="application/pdf,image/*">
                        </div>
                        <button type="submit" class="px-5 btn btn-magenta text-sm">Add</button>
                    </form>
                </template>
            </v-modal>
        </div>
        @endcan
    </div>
    <div class="flex-1">
        <form id="patch-form" method="POST" class="w-0">
            @csrf_token @method("PATCH")
        </form>
        <ul class="w-full border rounded-lg overflow-hidden mb-4">
            @forelse ($scholar->leaves as $leave)
            <li class="px-4 py-3 border-b last:border-b-0">
                <div class="flex items-center">
                    <h5 class="font-bold flex-1">
                        {{ $leave->reason }}
                        <div class="text-sm text-gray-500 font-bold">
                            ({{ $leave->from->format('Y-m-d') }} - {{$leave->to->format('Y-m-d')}})
                        </div>
                    </h5>
                    <a target="_blank" href="{{ route('scholars.leaves.application', $leave) }}"
                        class="btn inline-flex items-center ml-2">
                        <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                        Application
                    </a>
                    @if ($leave->isApproved() || $leave->isRejected())
                        <a target="_blank" href="{{ route('scholars.leaves.response_letter', $leave) }}" class="btn inline-flex items-center ml-2">
                            <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                            Response
                        </a>
                    @endif
                    <div class="flex items-center px-4">
                        <x-feather-icon name="{{ $leave->status->getContextIcon() }}"
                            class="h-current {{ $leave->status->getContextCSS() }} mr-2" stroke-width="2.5"></x-feather-icon>
                        <div class="capitalize">
                            {{ $leave->status }}
                        </div>
                    </div>
                    @can('recommend', $leave)
                        <button type="submit" form="patch-form"
                            class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                            formaction="{{ route('research.scholars.leaves.recommend', [$scholar, $leave]) }}">
                            Recommend
                        </button>
                    @endcan
                    @can('respond', $leave)
                        <button
                            class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg"
                            @click="$modal.show('respond-to-leave', {
                                'leave': {{ $leave }},
                                'scholar': {{ $scholar }},
                            })"> Respond
                        </button>
                    @endcan
                    @can('extend', $leave)
                        <button class="btn btn-magenta text-sm is-sm ml-4"
                            @click="$modal.show('apply-for-leave-modal', {
                                'extensionId': {{$leave->id}},
                                'extension_from_date': '{{ $leave->nextExtensionFrom()->format('Y-m-d') }}'
                            })">
                            Extend
                        </button>
                    @endcan
                </div>
                {{-- inception --}}
                <div class="ml-3 border-l-4">
                    @foreach($leave->extensions as $extensionLeave)
                    <div class="flex items-center ml-6 mt-4">
                        <h5 class="font-bold flex-1">
                            {{ $extensionLeave->reason }}
                            <div class="text-sm text-gray-500 font-bold">
                                (extension till {{$extensionLeave->to->format('Y-m-d')}})
                            </div>
                        </h5>
                        <a target="_blank" href="{{ route('research.scholars.leaves.application', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
                            <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                            Application
                        </a>
                        @if ($extensionLeave->isApproved() || $extensionLeave->isRejected())
                            <a target="_blank" href="{{ route('research.scholars.leaves.response_letter', [$scholar, $leave]) }}" class="btn inline-flex items-center ml-2">
                                <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                                Response
                            </a>
                        @endif
                        <div class="flex items-center px-4">
                            <x-feather-icon name="{{ $extensionLeave->status->getContextIcon() }}"
                                class="h-current {{ $extensionLeave->status->getContextCSS() }} mr-2" stroke-width="2.5">
                            </x-feather-icon>
                            <div class="capitalize">
                                {{ $extensionLeave->status }}
                            </div>
                        </div>
                        @can('recommend', $extensionLeave)
                        <button type="submit" form="patch-form"
                            class="px-4 py-2 mr-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded font-bold"
                            formaction="{{ route('research.scholars.leaves.recommend', [$scholar, $extensionLeave]) }}">
                            Recommend
                        </button>
                        @endcan
                        @can('respond', $extensionLeave)
                        <button
                            class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg"
                            @click="$modal.show('respond-to-leave', {
                                'leave': {{ $extensionLeave }},
                                'scholar': {{ $scholar }},
                            })"> Respond
                        </button>
                        @endcan
                    </div>
                    @endforeach
                </div>
            </li>
            @empty
            <li class="px-4 py-3 border-b last:border-b-0 text-center text-gray-700 font-bold">No Leaves</li>
            @endforelse
        </ul>
    </div>
</div>
