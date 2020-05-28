@dump(request()->all())

@extends('layouts.guest')
@section('body')

<div class="page-card p-4 w-1/2 mx-auto overflow-visible">
    <form class="space-y-2">
        <div>
            <label for="" class="w-full form-label">Subject</label>
            <input type="text" name="subject" class="w-full form-input">
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
            <input type="text" name="date" class="w-full form-input">
        </div>
        <div>
            <button class="btn btn-magenta w-full">Submit</button>
        </div>
    </form>
</div>
@endsection
