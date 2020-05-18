<div class="my-3 flex-1">
    {{-- TODO: Fix this show proper committee --}}
    <ul class="border rounded-lg overflow-hidden mb-4">
        @foreach ($scholar->advisors as $advisor)
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3 class="font-bold">{{ $advisor->name }}</h3>
            <div class="italic">{{ $advisor->designation }}, {{ $advisor->affiliation }}</div>
            <div>
                <a class="link" href="mailto:{{ $advisor->email }}">{{ $advisor->email }}</a>
                @isset($advisor->phone)<a class="link ml-2" href="tel:{{ $advisor->phone }}">{{ $advisor->phone }}</a>@endisset
            </div>
        </li>
        @endforeach
    </ul>
</div>
