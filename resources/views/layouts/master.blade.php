<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
    @routes
    <style>
        html,body {
            font-size: 14px;
        }
        [x-cloak] {
            opacity: 0;
        }
    </style>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @livewireScripts
</head>
<body class="font-sans leading-tight bg-gray-200 overflow-y-hidden">
    <div x-data x-cloak class="h-screen flex transition-opacity ease-in duration-500">
        <div class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
            @include('staff.partials.sidebar')
        </div>
        <div class="flex-1 flex flex-col h-full overflow-y-auto">
            @include('staff.partials.header')
            <main class="flex-1 h-full p-4 space-y-4">
                @yield('body')
            </main>
        </div>
        @stack('modals')
        @include('flash::message')
    </div>
    @stack('scripts')
</body>
</html>
