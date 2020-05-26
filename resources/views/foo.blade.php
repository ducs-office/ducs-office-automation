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
            <livewire:foo-test id="name" name="user_id" :value="request('user_id')">
        </div>
        <div>
            <label for="name" class="w-full form-label">Senders (Multi)</label>
            <livewire:foo-multi-test id="names" name="multi_user_id[]" :value="request('multi_user_id')">
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
