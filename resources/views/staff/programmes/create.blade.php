@extends('layouts.master')
@section('body')
<div class="page-card p-0 mx-auto overflow-hidden">
    <div class="p-6 border-b">
        <h2 class="text-2xl font-bold">New Programme</h2>
    </div>
    <div class="p-6">
        <livewire:new-programme-form />
    </div>
</div>
@endsection
