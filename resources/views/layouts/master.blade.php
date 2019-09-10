<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="font-sans leading-tight bg-gray-200 text-sm">
    <div id="app" class="min-h-screen flex flex-col">
        @include('partials.header')
        <div class="flex flex-1 h-full">
            @auth
            @include('partials.sidebar')
            @endauth
            <main class="p-4 pr-0 flex-1">
                @yield('body')
            </main>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
