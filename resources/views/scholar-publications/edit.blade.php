@extends('layouts.scholar-profile', ['scholar' => $scholar])
@section('body')
    <div class="page-card max-w-xl mx-auto my-4">
        <div class="page-header flex items-baseline">
            <h2 class="mr-6">Update Publication</h2>
        </div>
        <form id="remove-noc" method="POST" onsubmit="return confirm('Do you really want to delete co-author?');">
            @csrf_token @method('DELETE')
        </form>
        @include('_partials.forms.edit-publication', [
            'route' => route('scholars.publications.update', [$scholar, $publication])
        ])
    </div>
@endsection
