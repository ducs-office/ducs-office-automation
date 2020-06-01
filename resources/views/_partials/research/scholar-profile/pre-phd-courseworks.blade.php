<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Pre-PhD Coursework
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4">
            @foreach ($scholar->courseworks as $course)
                <li class="px-4 py-3 border-b last:border-b-0">
                    <div class="flex items-center">
                        <div class="w-24">
                            <span class="px-3 py-1 text-sm font-bold bg-magenta-200 text-magenta-800 rounded-full mr-4">{{ $course->type }}</span>
                        </div>
                        <h5 class="font-bold flex-1">
                            {{ $course->name }}
                            <span class="text-sm text-gray-500 font-bold"> ({{ $course->code }}) </span>
                        </h5>
                        @if ($course->pivot->completed_on)
                            <div class="flex items-center pl-4">

                                <a target="_blank"
                                href="{{ route('research.scholars.courseworks.marksheet', [ $scholar, $course->pivot])}}"
                                class="btn inline-flex items-center ml-2">

                                <x-feather-icon name="paperclip" class="h-current mr-2"></x-feather-icon>
                                    Marksheet
                                </a>
                                <div class="w-5 h-5 inline-flex items-center justify-center bg-green-500 text-white font-extrabold leading-none rounded-full mr-2">&checkmark;</div>
                                <div>
                                    Completed on {{ $course->pivot->completed_on->format('d M, Y') }}
                                </div>
                            </div>
                        @elsecan('scholars.coursework.complete', $scholar)
                            <button class="btn btn-magenta bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg"
                                @click="$modal.show('mark-coursework-completed', {
                                    'scholar': {{ $scholar }},
                                    'course': {{ $course }}
                                })">
                                Mark Completed
                            </button>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        @can('scholars.coursework.store', $scholar)
        <button class="w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-coursework-modal')">
            + Add Coursework
        </button>
        <v-modal name="add-coursework-modal" height="auto">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Add Coursework</h3>
                <form action="{{ route('research.scholars.courseworks.store', $scholar) }}"
                    method="POST" class="flex">
                    @csrf_token
                    <select id="course_ids" name="course_ids[]" class="w-full form-input rounded-r-none">
                        @foreach ($courses as $course)
                            <option value="{{$course->id}}">
                                [{{ $course->code }}] {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                </form>
            </div>
        </v-modal>
        @endcan
        @can('scholars.coursework.complete', $scholar)
        <v-modal name="mark-coursework-completed" height="auto">
            <template v-slot="{ data }">
                <form :action="route('research.scholars.courseworks.complete', [data('scholar'), data('course')])"
                method="POST" class="p-6" enctype="multipart/form-data">
                @csrf_token @method("PATCH")
                <h2 class="text-lg font-bold mb-8">Mark Course Work Complete</h2>
                    <div class="flex mb-2">
                        <div class="flex-1 mr-2 items-baseline">
                            <label for="completed_on" class="form-label mb-1 w-full">
                                Date of Completion
                                <span class="text-red-600 font-bold">*</span>
                            </label>
                            <input type="date" name="completed_on"
                            class="form-input w-full" id="completed_on">
                        </div>
                        <div class="flex-1 mb-2 items-baseline">
                            <label for="marksheet" class="form-label mb-1 w-full">
                                Upload Marksheet
                                <span class="text-red-600 font-bold">*</span>
                            </label>
                            <input id="marksheet" type="file" name="marksheet"
                                class="form-input w-full" accept="application/pdf,image/*">
                        </div>
                    </div>
                    <button class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 rounded font-bold btn">
                        Mark Completed
                    </button>
                </form>
            </template>
        </v-modal>
        @endcan
    </div>
</div>
