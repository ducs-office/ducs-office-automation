<footer class="w-full {{ $css ?? 'bg-magenta-800 text-gray-200' }} px-6 py-1 flex items-center justify-center flex-none text-sm text-white">
    <a href="http://cs.du.ac.in/" target="__blank" class="text-center"> &copy; {{ now()->format('Y') }} DUCS</a>
    <span class="mx-2">|</span>
    <p>Developed by <a href="{{ route('team') }}" target="__blank" class="underline text-center">Students</a></p>
</footer>
