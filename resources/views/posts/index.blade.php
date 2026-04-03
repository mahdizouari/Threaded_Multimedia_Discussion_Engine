<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Forum Posts') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div class="mb-4">
                    <a href="{{ route('posts.create') }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Create Post
                    </a>
                </div>
            @endauth

            @if ($posts->count())
                <div class="space-y-4">
                    @foreach ($posts as $post)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Category: {{ $post->category->label ?? 'No category' }} |
                                Author: {{ $post->user->name ?? 'Unknown' }}
                            </p>

                            <p class="mt-3 text-gray-700 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                            </p>

                            <div class="mt-4">
                                <a href="{{ route('posts.show', $post) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    Read More
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 dark:text-gray-300">No posts available yet.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>