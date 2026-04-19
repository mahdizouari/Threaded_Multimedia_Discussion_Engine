<x-app-layout page-title="Post Feed">
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-bold tracking-tight text-slate-900 sm:text-xl">
                    Post Feed
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Readable feed style: vote (Top / Flop), open a post to comment — 
                    aligned with your requirements and clear information presentation.
                </p>
            </div>
            @auth
                <a href="{{ route('posts.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-full bg-forum-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-forum-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-forum-500 focus-visible:ring-offset-2">
                    <span aria-hidden="true">+</span>
                    New Post
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="mx-auto max-w-3xl px-3 sm:px-4">
            <x-flash-message />

            @guest
                <div class="mb-4 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm"
                    role="region">
                    <a href="{{ route('login') }}" class="font-semibold text-forum-700 hover:underline">Log in</a>
                    to vote, publish, or comment.
                </div>
            @endguest

            @if ($posts->count())
                <ul class="space-y-2.5" role="list">
                    @foreach ($posts as $post)
                        @php
                            $reactions = $post->reactions;
                            $topCount = $reactions->filter(fn($r) => optional($r->appreciation)->type === 'TOP')->count();
                            $flopCount = $reactions->filter(fn($r) => optional($r->appreciation)->type === 'FLOP')->count();
                        @endphp
                        <li>
                            <article
                                class="flex overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm transition hover:border-slate-300">
                                <x-post-vote-rail :post="$post" dense />
                                <div class="min-w-0 flex-1 p-3 sm:p-4">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500">
                                        <span
                                            class="font-semibold text-forum-700">f/{{ Str::slug($post->category->label ?? 'general', '-') }}</span>
                                        <span aria-hidden="true">·</span>
                                        <span>By <span
                                                class="font-medium text-slate-700">{{ $post->user->name ?? 'Unknown' }}</span></span>
                                        <span aria-hidden="true">·</span>
                                        <time
                                            datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->diffForHumans() ?? '—' }}</time>
                                        @auth
                                            @if (auth()->id() === $post->user_id && !$post->is_approved)
                                                <span
                                                    class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800">Pending</span>
                                            @endif
                                        @endauth
                                    </div>
                                    <h3 class="mt-1.5 text-base font-semibold leading-snug text-slate-900 sm:text-lg">
                                        <a href="{{ route('posts.show', $post) }}"
                                            class="hover:text-forum-700 focus:outline-none focus-visible:underline">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-600">
                                        {{ Str::limit(strip_tags($post->content), 220) }}
                                    </p>
                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500">
                                        <a href="{{ route('posts.show', $post) }}#commentaires"
                                            class="inline-flex items-center gap-1 rounded-full px-2 py-1 hover:bg-slate-100 hover:text-slate-800">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Comments
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="rounded-lg border border-dashed border-slate-300 bg-white py-14 text-center shadow-sm">
                    <p class="text-slate-600">No posts for now.</p>
                    @auth
                        <a href="{{ route('posts.create') }}"
                            class="mt-3 inline-block text-sm font-bold text-forum-700 hover:underline">
                            Create the first post
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>