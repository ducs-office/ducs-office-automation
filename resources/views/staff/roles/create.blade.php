@extends('layouts.master')
@section('body')
    <div class="page-card p-0">
        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold">Create New Role</h2>
        </div>
        <div class="px-6 py-4">
            @include('_partials.forms.create-role')
        </div>
    </div>
@endsection
