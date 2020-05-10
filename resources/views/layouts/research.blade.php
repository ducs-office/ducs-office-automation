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
    <div x-data="{ ...modalsRoot() }" class="h-screen flex">
        @auth('web')
        <div class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
            @auth @include('staff.partials.sidebar') @endauth
        </div>
        @endauth
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            @auth('web')
            @include('teachers.partials.header')
            @elseauth('scholars')
            @include('scholars.partials.header')
            @endauth

            @yield('body')
        </main>
        @include('flash::message')
    </div>
    @include('flash::message')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
