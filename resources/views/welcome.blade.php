@extends('layouts.base')

@section('title', 'Welcome')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16 text-center">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        Welcome to Forum Multimédia
    </h1>

    <p class="mt-4 text-gray-600 dark:text-gray-300">
        Share multimedia posts, join discussions, and discover community content.
    </p>

    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
        @auth
            <a href="{{ route('posts.index') }}"
               class="inline-flex justify-center px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                Go to Forum
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-flex justify-center px-5 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                Log in
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="inline-flex justify-center px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white font-semibold hover:bg-gray-50 dark:hover:bg-gray-700">
                    Register
                </a>
            @endif
        @endauth
    </div>
</div>
@endsection