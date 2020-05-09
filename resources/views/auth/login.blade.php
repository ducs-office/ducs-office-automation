@extends('layouts.guest')
@section('body')
    <div class="page-card mb-6 sm:mb-12 max-w-lg mx-auto">
        <h3 class="page-header">Login</h3>
        <form action="{{ route('login') }}" method="POST" class="px-6 space-y-3">
            @csrf_token
            <div x-data="{ guard: 'web' }">
                <div class="-mx-6 px-6 flex space-x-4 border-b">
                    <button x-on:click="guard = 'web'" type="button"
                        x-bind:class="{'-mb-px': guard === 'web' }"
                        class="relative px-3 py-2 border border-b-0 rounded-t
                            bg-white overflow-hidden
                            hover:text-magenta-700 focus:text-magenta-700 hover:underline
                            focus:underline focus:outline-none">
                        <div x-show="guard === 'web'" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform scale-x-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-end="transform scale-x-0"
                            class="absolute top-0 inset-x-0 h-0 rounded-t border-t-4 border-magenta-700"></div>
                        Regular Login
                    </button>
                    <button x-on:click="guard = 'scholars'" type="button"
                        x-bind:class="{'-mb-px': guard === 'scholars' }"
                        class="relative px-3 py-2 border border-b-0 rounded-t
                            bg-white overflow-hidden
                            hover:text-magenta-700 focus:text-magenta-700 hover:underline
                            focus:underline focus:outline-none">
                        <div x-show="guard == 'scholars'"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform scale-x-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-end="transform scale-x-0"
                            class="absolute top-0 inset-x-0 h-0 rounded-t border-t-4 border-magenta-700"></div>
                        Scholar Login
                    </button>
                </div>
                <input type="hidden" name="type" x-bind:value="guard">
            </div>
            <div>
                <label class="w-full form-label mb-1" for="email">Email</label>
                <input type="email" name="email" class="w-full form-input{{ $errors->has('email') ? ' border-red-600' : '' }}"
                    placeholder="e.g. johndoe@example.com" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                <p class="text-red-600 mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>
            <div>
                <label class="w-full form-label mb-1" for="password">Password</label>
                <input type="password" name="password" class="w-full form-input" placeholder="Enter your password here..." required>
            </div>
            <div>
                <label for="remember" class="flex items-center">
                    <input type="checkbox" name="remember"
                    id="remember" class="form-checkbox"
                    {{ old('remember', false) ? 'checked' : ''}}>
                    <span class="form-label ml-2">Remember me</span>
                </label>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full btn btn-magenta py-2">Login</button>
            </div>
        </form>
    </div>
@endsection
