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
            html,
            body {
                font-size: 14px;
            }
        </style>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans leading-tight bg-gray-200">
        <div x-data="{ ...modalsRoot() }" class="h-screen flex">
            <div class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
                @include('scholars.partials.sidebar')
            </div>
            <div class="flex-1 flex flex-col h-full overflow-y-auto">
                @include('scholars.partials.header')
                <main class="flex-1 h-full p-4 space-y-4">
                    @yield('banner')
                    @yield('body')
                </main>
            </div>
        </div>
        @include('flash::message')
    </body>
</html>
