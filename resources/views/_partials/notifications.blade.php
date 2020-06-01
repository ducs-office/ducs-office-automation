<div class="relative">
    <button class="p-1 text-gray-700 hover:text-magenta-700" x-on:click="notificationDropdown = !notificationDropdown">
        @php
            $notificationCount = Auth::user()->unreadNotifications->count();
        @endphp
        @if($notificationCount)
        <p class="h-current -mr-3 -mb-1 font-bold bg-red-700 text-white rounded-full">
            {{ $notificationCount }}
        </p>
        @endif
        <x-feather-icon name="bell" class="h-6">Notifications</x-feather-icon>
    </button>
    <div x-show="notificationDropdown"
        x-on:click.away="notificationDropdown = false"
        class="absolute right-0 mt-2 page-card border p-0 w-64 max-h-screen-1/2 overflow-y-auto z-10"
        x-transition:enter="transition ease-in duration-150"
        x-transition:enter-start="transform origin-top-right scale-0 opacity-0"
        x-transition:enter-end="transform origin-top-right scale-100 opacity-100"
        x-transition:leave="transition ease-out duration-150"
        x-transition:leave-start="transform origin-top-right scale-100 opacity-100"
        x-transition:leave-end="transform origin-top-right scale-0 opacity-0">
        <h5 class="px-4 font-bold px-4 py-2 border-b">Notifications</h5>
        @forelse (Auth::user()->notifications as $notification)
            <p class="px-4 py-2 border-t{{ $notification->read_at ? ' bg-gray-100 ' : ' bg-white font-bold ' }}hover:bg-gray-200">
                {{ $notification->data['inform'] }}
            </p>
            @php
                $notification->markAsRead();
            @endphp
        @empty
            <p class="px-4 py-2 text-gray-500    text-center">No notifications.</p>
        @endforelse
    </div>
</div>
