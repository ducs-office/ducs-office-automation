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
    <div id="app">
        <toggle-visibility :shown="$window.innerWidth > 800" class="flex h-screen">
            <template v-slot="sidebar">
                    <transition enter-class="translate-x-back-100"
                        leave-to-class="translate-x-back-100"
                        enter-active-class="transition-transform"
                        leave-active-class="transition-transform">
                        <div v-show="sidebar.isVisible"
                            class="bg-magenta-800 text-white w-80 flex flex-col flex-shrink-0">
                            @auth @include('partials.sidebar') @endauth
                        </div>
                    </transition>
                    <main class="flex-1 overflow-x-hidden overflow-y-auto">
                        @include('partials.header')
                        @yield('body')
                    </main>
                    @include('flash::message')
            </template>
        </toggle-visibility>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
