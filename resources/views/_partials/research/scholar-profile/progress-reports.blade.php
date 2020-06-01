{{--Progress Reports--}}

<div class="page-card p-6 flex overflow-visible space-x-6">
    <div class="w-64 pr-4 relative -ml-8 my-2">
        <h3 class="relative pl-8 pr-4 py-2 font-bold bg-magenta-700 text-white shadow">
            Progress Reports
        </h3>
        <svg class="absolute left-0 w-2 text-magenta-900" viewBox="0 0 10 10">
            <path fill="currentColor" d="M0 0 L10 0 L10 10 L0 0"></path>
        </svg>
    </div>
    <div class="flex-1">
        <ul class="border rounded-lg overflow-hidden mb-4 divide-y">
            @forelse ($scholar->progressReports as $progressReport)
                @can('view', $progressReport)
                <li class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 mr-4">
                            <p class="font-bold w-32">{{ $progressReport->date->format('d F Y') }}</p>
                            <a  target="_blank"
                                href="{{ route('scholars.progress_reports.show', [$scholar, $progressReport]) }}"
                                class="inline-flex items-center underline px-3 py-1 bg-gray-100 text-gray-900 rounded font-bold">
                                <x-feather-icon name="paperclip" class="h-4 mr-2">Attachment</x-feather-icon>
                                Attachment
                            </a>
                        </div>
                        <div class="flex items-center space-x-4 mr-4">
                            <span class="px-3 py-1 text-sm font-bold rounded-full ml-auto flex-shrink-0
                                {{ $progressReport->recommendation->getContextCSS() }}">
                                {{ $progressReport->recommendation }}
                            </span>
                            @can('delete', $progressReport)
                            <form method="POST" action="{{ route('scholars.progress_reports.destroy', [$scholar, $progressReport]) }}"
                                onsubmit="return confirm('Do you really want to delete this preogress report?');">
                                @csrf_token
                                @method('DELETE')
                                <button type="submit" class="p-1 hover:bg-gray-200 text-red-700 rounded">
                                    <x-feather-icon name="trash-2" stroke-width="2.5" class="h-current">Delete</x-feather-icon>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </li>
                @endcan
            @empty
                <li class="px-4 py-3 text-center text-gray-700 font-bold">No Progress Reports yet.</li>
            @endforelse
        </ul>
        @can('create', \App\Models\ProgressReport::class)
        <button class="mt-2 w-full btn btn-magenta rounded-lg py-3" @click="$modal.show('add-progress-reports-modal')">
            + Add Progress Reports
        </button>
        <v-modal name="add-progress-reports-modal" height="auto">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Add Progress Report</h3>
                <form action="{{ route('scholars.progress_reports.store', $scholar) }}" method="POST"
                    class="px-6" enctype="multipart/form-data">
                    @csrf_token
                    <div class="mb-2 flex items-center">
                        <div class="w-1/2 mr-1">
                            <label for="date" class="mb-1 w-full form-label">Date
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="date" id="date" class="w-full form-input" required>
                        </div>
                        <div class="w-1/2 ml-1">
                            <label for="recommendation" class="mb-1 w-full form-label">Recommendation
                                <span class="text-red-600">*</span>
                            </label>
                            <select class="w-full form-input block" name="recommendation" required>
                                <option class="text-gray-600" selected disabled value="">Select Recommendation</option>
                                @foreach (App\Types\ProgressReportRecommendation::values() as $recommendation)
                                    <option value="{{ $recommendation }}" class="text-gray-600"> {{ $recommendation }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="progress_report" class="w-full form-label mb-1">Upload Progress Report
                            <span class="text-red-600">*</span>
                        </label>
                        <input type="file" name="progress_report" id="progress_report" class="w-full mb-1" accept="document/*" required>
                    </div>
                    <button type="submit" class="px-5 btn btn-magenta text-sm rounded-l-none">Add</button>
                </form>
            </div>
        </v-modal>
        @endcan
    </div>
</div>
