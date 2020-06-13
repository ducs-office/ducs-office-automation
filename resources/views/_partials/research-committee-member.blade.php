<img src="{{ $member->avatar_url }}" alt="{{ $member->name }}"
    class="w-10 h-10 rounded-full overflow-hidden bg-gray-400">
<div>
    <h3 class="font-bold">{{ $member->name }}</h3>
    <p>
        <a class="link" href="mailto:{{ $member->email }}">{{ $member->email }}</a>
        @isset($member->phone)<a class="link ml-2" href="tel:{{ $member->phone }}">{{ $member->phone }}</a>@endisset
    </p>
    <div class="italic">{{ $member->designation ?? 'Unknown' }}, {{ $member->affiliation }}</div>
</div>