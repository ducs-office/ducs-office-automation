<div class="my-3 flex-1">
    {{-- TODO: Fix this show proper committee --}}
    <ul class="border rounded-lg overflow-hidden mb-4">
        @foreach ($scholar->advisors as $advisor)
        <li class="px-4 py-3 border-b last:border-b-0">
            <h3 class="font-bold">{{ $member->name }}</h3>
            <div class="italic">{{ $member->designation }}, {{ $member->affiliation }}</div>
            <div>
                <a class="link" href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                @isset($member->phone)<a class="link ml-2" href="tel:{{ $member->phone }}">{{ $member->phone }}</a>@endisset
            </div>
        </li>
        @endforeach
    </ul>
</div>
