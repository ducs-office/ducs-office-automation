<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Team</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @routes
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-200 overflow-x-hidden">
    @include('header')
    <main>
        {{-- Design 2 --}}
        <div class="max-w-xl mx-auto my-4">
            <div class="rounded-full w-64 h-64 border-4 border-magenta-800 my-2 mx-auto bg-cover bg-center" 
                style="background-image: url('{{ asset('images/person.jpeg') }}');">
                <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center ml-auto mr-12" href="#" >
                    <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="twitter"> Twitter Link </x-feather-icon> 
                </a>
                <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto ml-2" href="#" >
                    <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="github"> Github Link </x-feather-icon> 
                </a>
                <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto -ml-3 mt-20" href="#" >
                    <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="linkedin"> Linkedin Link </x-feather-icon> 
                </a>
                <p class="text-center my-2 font-bold text-white text-lg underline"> Ruman Saleem </p>
                <p class="text-center my-1 text-white text-sm text-white"> MSC CS'20 </p>
            </div>
            <div class="flex justify-between">
                <div class="rounded-full w-64 h-64 border-4 border-magenta-800 my-2 bg-cover bg-center" 
                    style="background-image: url('{{ asset('images/person.jpeg') }}');">
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center ml-auto mr-12" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="twitter"> Twitter Link </x-feather-icon> 
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto ml-2" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="github"> Github Link </x-feather-icon> 
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto -ml-3 mt-20" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="linkedin"> Linkedin Link </x-feather-icon> 
                    </a>
                    <p class="text-center my-2 font-bold text-white text-lg underline"> Swati Gautam </p>
                    <p class="text-center my-1 text-white text-sm text-white"> MSC CS'20 </p>
                </div>
                <div class="rounded-full w-64 h-64 border-4 border-magenta-800 my-2 bg-cover bg-center" 
                    style="background-image: url('{{ asset('images/person.jpeg') }}');">
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center ml-auto mr-12" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="twitter"> Twitter Link </x-feather-icon> 
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto ml-2" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="github"> Github Link </x-feather-icon> 
                    </a>
                    <a class="rounded-full w-8 h-8 bg-magenta-800 flex justify-center items-center mr-auto -ml-3 mt-20" href="#" >
                        <x-feather-icon class="h-8 p-2 fill-current text-white font-bold" name="linkedin"> Linkedin Link </x-feather-icon> 
                    </a>
                    <p class="text-center my-2 font-bold text-white text-lg underline"> Tanya Singhal </p>
                    <p class="text-center my-1 text-white text-sm text-white"> MSC CS'20 </p>
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
    @include('footer')
</body>
</html>