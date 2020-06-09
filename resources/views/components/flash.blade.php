<div class="fixed min-w-64 max-w-1/3 md:max-w-1/4 flex flex-col right-0 inset-y-0 mb-4 mr-4 justify-end z-50 pointer-events-none space-y-4">
    @foreach($messages as $index => $message)
    <div x-data="{
        visible: false,
        duration: 6,
        delay: ({{ $index }} * .1)
    }" x-init="
        setTimeout(() => {
            visible = true;
            setTimeout(() => visible = false, duration*1000);
        }, delay*1000)"
        x-show="visible"
        x-transition:enter="transition-transform duration-300"
        x-transition:leave="transition-transform duration-300"
        x-transition:enter-start="transform translate-x-full"
        x-transition:leave-end="transform translate-x-full"
        x-transition:enter-end="transform translate-x-0"
        x-transition:leave-start="transform translate-x-0"
        class="border-l-4{{ $getBorderColor($message) }}bg-white rounded p-2 pl-3 w-full shadow-md flex items-center space-x-4"
        key="{{ $index }}">
        <x-feather-icon name="{{ $getIcon($message) }}" class="{{ $getTitleColor($message) }} h-8 flex-none"></x-feather-icon>
        <div class="flex-1">
            <h5 class="text-lg{{ $getTitleColor($message) }}font-bold">{{ $getTitle($message) }}</h5>
            <p class="{{$getTextColor($message)}}">{{ $message->message }}</p>
        </div>
    </div>
    @endforeach
</div>
