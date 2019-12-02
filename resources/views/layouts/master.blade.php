<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @routes
    <style>
        html,body {
            font-size: 14px;
        }
    </style>
</head>
<body class="font-sans leading-tight bg-gray-200 overflow-y-hidden">
    <div id="app" class="flex h-screen">
        @auth @include('partials.sidebar') @endauth
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            @include('partials.header')
            @yield('body')
        </main>
        @include('flash::message')
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
