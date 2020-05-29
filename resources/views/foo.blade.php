@extends('layouts.guest')
@section('body')
<div class="page-card p-4 w-1/2 mx-auto overflow-visible">
    <form class="space-y-2">
        <div>
            <label for="" class="w-full form-label">Subject</label>
            <select type="text" name="subject" class="w-full form-select">
                @foreach(['John', 'Mary', 'Jane', 'Foo', 'Bar', 'hello', 'there'] as $index => $user)
                    <option selected="false" value="{{$index}}">{{ $user }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="name" class="w-full form-label">Sender</label>
            <x-select id="select-names" name="name">
                @foreach(['John', 'Mary', 'Jane', 'Foo', 'Bar', 'hello', 'there'] as $index => $user)
                <div class="px-4 py-2" value="{{ $index }}">
                    <span>{{ $index }} => {{ $user }}</span>
                </div>
                @endforeach
            </x-select>
        </div>
        <div>
            <label for="" class="w-full form-label">Date</label>
            <x-select id="select-names" name="names[]" :multiple="true">
                @foreach(['John', 'Mary', 'Jane', 'Foo', 'Bar', 'hello', 'there'] as $index => $user)
                <div class="px-4 py-2" value="{{ $index }}">
                    <span>{{ $index }} => {{ $user }}</span>
                </div>
                @endforeach
            </x-select>
        </div>
        <div>
            <button class="btn btn-magenta w-full">Submit</button>
        </div>
    </form>
</div>
@endsection
