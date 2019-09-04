@extends('layouts.master')
@section('body')
<form action="" method="POST" class="w-1/3 mx-auto leading-none mt-12">
    {{ csrf_field() }}
    <h3 class="mb-4 text-2xl">Login</h3>
    <div class="mb-2">
        <input type="text" name="email" class="w-full border px-3 py-2 rounded" placeholder="email">
    </div>
    <div class="mb-2">
        <input type="password" name="password" class="w-full border px-3 py-2 rounded" placeholder="Password">
    </div>
    <div class="mb-2">
        <button type="submit" class="bg-blue-500 text-white px-3 py-2 font-bold rounded">Login</button>
    </div>
</form>
`
@endsection