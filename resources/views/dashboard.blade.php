@extends('layouts.base')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
        Dashboard
    </h1>

    <p class="mt-2 text-gray-600 dark:text-gray-300">
        Welcome back, {{ auth()->user()->name }}.
    </p>

    <div class="mt-6 flex gap-4">
        <a href="{{ route('posts.index') }}" class="px-4 py-2 rounded bg-blue-600 text-white">
            Go to Posts
        </a>

        <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded bg-gray-700 text-white">
            Manage Categories
        </a>
    </div>
</div>
@endsection