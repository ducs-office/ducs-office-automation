<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Team | Department of Computer Science</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-200">
    <div class="flex-1 flex flex-col h-full">
        @include('_partials.layouts.header')
        <main class="flex-1 h-auto p-4 md:p-8 space-y-4">
            <h2 class="text-3xl font-bold text-center mb-8">Our Development Team</h2>
            {{-- Design 2 --}}
            <div class="max-w-xl mx-auto">
                <div class="flex flex-col rounded-full w-56 h-56 border-4 border-magenta-800 my-2 mx-auto bg-cover bg-center"
                    style="background-image: url('https://gravatar.com/avatar/9d7abb7f3b1ddb8b718a654f80790ef0?s=512');">
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 ml-auto mr-8"
                        target="__blank" href="https://twitter.com/ruman_saleem" >
                        <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="twitter">
                            Twitter Link
                        </x-feather-icon>
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto"
                        target="__blank" href="https://github.com/rumansaleem" >
                        <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="github">
                            Github Link
                        </x-feather-icon>
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 ml-auto -mr-3" target="__blank"
                        href="https://rumansaleem.github.io">
                        <x-feather-icon class="h-8 p-2 text-white font-bold" name="globe">
                            Website
                        </x-feather-icon>
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto -ml-3 mt-8"
                        target="__blank" href="https://www.linkedin.com/in/ruman-saleem/" >
                        <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="linkedin">
                            Linkedin Link
                        </x-feather-icon>
                    </a>
                    <p class="text-center mt-auto mb-2 font-bold text-magenta-800 text-xl">
                        <span class="bg-gray-200 shadow-lg px-4 py-1 rounded-md"> Ruman Saleem </span>
                    </p>
                </div>
                <div class="flex justify-between">
                    <div class="flex flex-col rounded-full w-56 h-56 border-4 border-magenta-800 my-2 bg-cover bg-center"
                        style="background-image: url('https://s.gravatar.com/avatar/1a5fe449d7d786f5d9954b3afe5a4343?s=512');">
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 ml-auto mr-8"
                            target="__blank" href="https://twitter.com/gautam_swati26" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="twitter">
                                Twitter Link
                            </x-feather-icon>
                        </a>
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto"
                            target="__blank" href="https://github.com/gautamswati" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="github">
                                Github Link
                            </x-feather-icon>
                        </a>
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto -ml-3 mt-16"
                            target="__blank" href="https://www.linkedin.com/in/swati-gautam-694169173/" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="linkedin">
                                Linkedin Link
                            </x-feather-icon>
                        </a>
                        <p class="text-center mt-auto mb-2 font-bold text-magenta-800 text-xl">
                            <span class="bg-gray-200 shadow-lg px-4 py-1 rounded-md"> Swati Gautam </span>
                        </p>
                    </div>
                    <div class="flex flex-col rounded-full w-56 h-56 border-4 border-magenta-800 my-2 bg-cover bg-center"
                        style="background-image: url('https://s.gravatar.com/avatar/62abe2d898363acf2d7a6c37670157d5?s=512');">
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 ml-auto mr-8"
                            target="__blank" href="https://twitter.com/tanya__singhal" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="twitter">
                                Twitter Link
                            </x-feather-icon>
                        </a>
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto"
                            target="__blank" href="https://github.com/tanyasinghal" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="github">
                                Github Link
                            </x-feather-icon>
                        </a>
                        <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center transform transition duration-150 hover:scale-110 mr-auto -ml-3 mt-16"
                            target="__blank" href="https://www.linkedin.com/in/tanya-singhal-66aa28116/" >
                            <x-feather-icon class="h-8 p-2 fill-current stroke-1 text-white font-bold" name="linkedin">
                                Linkedin Link
                            </x-feather-icon>
                        </a>
                        <p class="text-center mt-auto mb-2 font-bold text-magenta-800 text-xl">
                            <span class="bg-gray-200 shadow-lg px-4 py-1 rounded-md"> Tanya Singhal </span>
                        </p>
                    </div>
                    </div>
                </div>
            </div>

            {{-- Design 1 --}}

            {{-- <div class="flex justify-center m-4 w-full p-6">
                <div class="page-card mx-8 my-4 p-6">
                    <div class="flex justify-center m-2">
                        <img src="{{ asset('images/gravatar.jpeg') }}" class="rounded-full h-40 w-40">
                    </div>
                    <p class="font-bold m-2 text-gray-700 text-lg text-center tracking-wide"> Keee Sin yun Chung </p>
                    <p class="font-bold m-2 text-gray-700 text-sm font-normal text-center"> MSc CS'20 </p>
                    <hr class="font-bold border-magenta-800 my-4 mx-10"> </hr>
                    <div class="flex justify-center m-2">
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="github" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="linkedin" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="twitter" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                    </div>
                </div>
                <div class="page-card mx-8 my-4 p-6">
                    <div class="flex justify-center m-2">
                        <img src="{{ asset('images/gravatar.jpeg') }}" class="rounded-full h-40 w-40">
                    </div>
                    <p class="font-bold m-2 text-gray-700 text-lg text-center tracking-wide"> Keee Sin yun Chung </p>
                    <p class="font-bold m-2 text-gray-700 text-sm font-normal text-center"> MSc CS'20 </p>
                    <hr class="font-bold border-magenta-800 my-4 mx-10"> </hr>
                    <div class="flex justify-center m-2">
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="github" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="linkedin" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="twitter" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                    </div>
                </div>
                <div class="page-card mx-8 my-4 p-6">
                    <div class="flex justify-center m-2">
                        <img src="{{ asset('images/gravatar.jpeg') }}" class="rounded-full h-40 w-40">
                    </div>
                    <p class="font-bold m-2 text-gray-700 text-lg text-center tracking-wide"> Keee Sin yun Chung </p>
                    <p class="font-bold m-2 text-gray-700 text-sm font-normal text-center"> Msc CS'20 </p>
                    <hr class="font-bold border-magenta-800 my-4 mx-10"> </hr>
                    <div class="flex justify-center m-2">
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="github" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="linkedin" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                        <a href="#" class="m-1 rounded-full h-8 w-8 flex justify-center items-center bg-magenta-800">
                            <x-feather-icon name="twitter" class="font-bold text-white p-1 h-6 stroke-width-2"> link </x-feather-icon>
                        </a>
                    </div>
                </div>
            </div> --}}
        </main>
        @include('_partials.layouts.footer')
    </div>
</body>
</html>
