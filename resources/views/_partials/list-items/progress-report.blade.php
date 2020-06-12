@can('view', $progressReport)
    <li class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 mr-4">
                <p class="font-bold w-32">{{ $progressReport->date->format('d F Y') }}</p>
                <a  target="_blank"
                    href="{{ route('scholars.progress-reports.show', [$scholar, $progressReport]) }}"
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
                <form method="POST" action="{{ route('scholars.progress-reports.destroy', [$scholar, $progressReport]) }}"
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