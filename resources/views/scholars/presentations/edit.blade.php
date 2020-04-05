@extends('layouts.scholars')
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Update Presentation</h2>
        </div>
        <form action="{{ route('scholars.profile.presentation.update' , $presentation )}}" method="post" class="px-6">
            @csrf_token
            @method('PATCH')
            
        </form>
    </div>
@endsection