<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        html,body {
            font-size: 14px;
        }
    </style>
</head>
<body class="font-sans leading-tight bg-gray-200">
    <div id="app" class="flex min-h-screen">
        @auth @include('partials.sidebar') @endauth
        <main class="flex-1 overflow-x-hidden">
            @include('partials.header')
            @yield('body')
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
