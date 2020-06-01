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
    <div x-data="{
            sidebar: window.innerWidth > 768,
            userDropdown: false,
            notificationDropdown: false
        }"
        x-cloak class="h-screen flex transition-opacity ease-in duration-500">
        <div id="sidebar" x-show="sidebar"
            role="navigation" x-bind:aria-expanded="sidebar" aria-owns="sidebar-toggle"
            class="bg-magenta-800 text-white w-80 max-w-sm flex flex-col flex-shrink-0 h-full overflow-y-auto"
            x-transition:enter="transition-transform ease-in duration-300"
            x-transition:enter-start="transform -translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition-transform ease-out duration-300"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform -translate-x-full">
            @include('staff.partials.sidebar')
        </div>
        <div class="flex-1 flex flex-col h-full overflow-y-auto transition duration-300">
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
