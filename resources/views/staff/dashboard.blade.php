@extends('layouts.master')
@section('body')
<div class="page-card p-6">
    <h2 class="text-3xl">Hello {{ optional(auth()->user())->first_name ?? 'Unknown' }}!</h2>
</div>
@endsection
