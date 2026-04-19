@extends('layouts.pulse')

@section('content')

    <style>
        .back-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 24px;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            background: rgba(0,0,0,0.02);
            text-decoration: none;
        }
        .back-nav:hover {
            background: var(--bg-glass-hover);
            color: var(--text-primary);
            transform: translateX(-4px);
        }
    </style>

    <a href="{{ route('home') }}" class="back-nav">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Back to Feed
    </a>

    {{-- ====================================================
         PROFILE HEADER
         ISO 9241 : Identification claire, hiérarchie visuelle
    ===================================================== --}}
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-glass);
                padding: 32px; margin-bottom: 24px; position: relative; overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">

        {{-- Decorative gradient band --}}
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 96px;
                    background: var(--accent-gradient); opacity: 0.08; pointer-events: none;"></div>

        <div style="position: relative; display: flex; align-items: flex-end; gap: 24px; flex-wrap: wrap;">

            {{-- Avatar --}}
            <img src="{{ $user->profile && $user->profile->avatar_path
                            ? asset('storage/' . $user->profile->avatar_path)
                            : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($user->username) }}"
                 alt="Profile avatar of {{ $user->username }}"
                 style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover;
                        border: 4px solid white; box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                        flex-shrink: 0; background: #f1f5f9; margin-top: 24px;">

            {{-- User info block --}}
            <div style="flex: 1; min-width: 0; padding-bottom: 4px;">

                {{-- Username + role badge --}}
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 6px;">
                    <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary);
                               margin: 0; letter-spacing: -0.4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        u/{{ $user->username }}
                    </h1>
                    @if($user->role === 'admin')
                        <span style="font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px;
                                     background: #ef4444; color: white; letter-spacing: 0.5px;">ADMIN</span>
                    @elseif($user->role === 'moderator')
                        <span style="font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px;
                                     background: #8b5cf6; color: white; letter-spacing: 0.5px;">MOD</span>
                    @endif
                </div>

                {{-- Stats row --}}
                <div style="display: flex; gap: 20px; flex-wrap: wrap; color: var(--text-secondary);
                            font-size: 13.5px; font-weight: 500; margin-bottom: 14px;">
                    <div style="display: flex; gap: 6px; align-items: center;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <strong style="color: var(--text-primary);">{{ $posts->total() }}</strong> posts published
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Member since {{ $user->created_at->format('F Y') }}
                    </div>
                </div>

                {{-- Interest badges --}}
                @if($user->interests && $user->interests->count() > 0)
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        @foreach($user->interests as $interest)
                            <span style="background: rgba(124, 58, 237, 0.07); color: var(--accent-primary);
                                         padding: 4px 10px; border-radius: var(--radius-pill);
                                         font-size: 12px; font-weight: 600; border: 1px solid rgba(124,58,237,0.15);">
                                {{ $interest->label }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Action button --}}
            @auth
                <div style="padding-bottom: 4px; flex-shrink: 0;">
                    @if(Auth::id() !== $user->id)
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('messages.show', $user->id) }}"
                               class="btn btn-primary"
                               style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; letter-spacing: 0.3px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                Send Message
                            </a>

                        </div>
                    @else
                        <a href="{{ route('profile.edit') }}"
                           class="btn btn-outline"
                           style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            Edit Profile
                        </a>
                    @endif
                </div>
            @endauth

        </div>
    </div>

    {{-- ====================================================
         POSTS SECTION HEADER
         ISO 9241 : Navigation claire, titres en H2
    ===================================================== --}}
    <div style="display: flex; align-items: center; justify-content: space-between;
                margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-glass);">
        <h2 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin: 0;
                   display: flex; align-items: center; gap: 8px; text-transform: uppercase;
                   letter-spacing: 0.5px;">
            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            Posts by u/{{ $user->username }}
        </h2>
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
            {{ $posts->total() }} {{ $posts->total() === 1 ? 'post' : 'posts' }}
        </span>
    </div>

    {{-- ====================================================
         POSTS FEED
         ISO 9241 : Consistance avec le feed principal
    ===================================================== --}}    <div id="feed-container" class="feed-container">
        @forelse($posts as $post)
            <article class="post">
                <div class="post-header">
                    <div class="post-header-left">
                        <a href="{{ route('users.show', $post->user->username) }}" class="community-avatar" style="display: block; width: fit-content;">
                            <img src="{{ $post->user->profile && $post->user->profile->avatar_path ? asset('storage/' . $post->user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($post->user->username) }}" alt="author" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        </a>
                        <div>
                            <div class="post-community" style="display: flex; gap: 6px; align-items: center; margin-bottom: 2px;">
                                <a href="{{ route('users.show', $post->user->username) }}" class="post-author" style="font-weight: 800; color: var(--text-primary); text-decoration: none;">u/{{ $post->user->username }}</a>
                                @if(isset($post->user) && $post->user->isAdmin())
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white;">ADMIN</span>
                                @elseif(isset($post->user) && $post->user->isModerator())
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white;">MOD</span>
                                @endif
                            </div>
                            <div class="post-meta">
                                <span style="color: var(--accent-primary); font-weight: 600;">p/{{ strtolower($post->category->label ?? 'general') }}</span> • {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(Auth::id() !== $post->user_id)
                            <a href="{{ route('messages.show', $post->user_id) }}" class="interaction-btn"
                                style="padding: 6px 12px; border-radius: var(--radius-pill); font-size: 11px; transition: all 0.2s;"
                                onmouseover="this.style.color='var(--accent-primary)'; this.style.background='rgba(124, 58, 237, 0.1)';"
                                onmouseout="this.style.color=''; this.style.background='';">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                Chat
                            </a>
                        @endif
                    @endauth
                </div>

                <a href="{{ route('posts.show', $post->id) }}" style="text-decoration: none; display: block; color: inherit; cursor: pointer;">
                    <h2 class="post-title">{{ $post->title }}</h2>
                    <div class="post-content">
                        {{ Str::limit($post->content, 300) }}
                    </div>

                    @if($post->image_path)
                        <div class="post-media">
                            <img src="{{ filter_var($post->image_path, FILTER_VALIDATE_URL) ? $post->image_path : asset('storage/' . $post->image_path) }}"
                                alt="Post image">
                        </div>
                    @endif
                </a>

                <div class="post-actions">
                    <div class="action-group" data-post-id="{{ $post->id }}">
                        @if(Auth::check())
                            <button type="button"
                                class="action-btn like-btn {{ $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'TOP')->count() ? 'active' : '' }}"
                                title="Like"
                                onclick="reactToPost({{ $post->id }}, 'TOP', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                                    <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                </svg>
                            </button>
                            <span class="vote-count like-count" data-post-likes="{{ $post->id }}">{{ $post->reactions->where('appreciation.type', 'TOP')->count() }}</span>
                        @else
                            <div class="action-btn like-btn" style="cursor: default; opacity: 0.5;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                                    <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                </svg>
                            </div>
                            <span class="vote-count like-count">{{ $post->reactions->where('appreciation.type', 'TOP')->count() }}</span>
                        @endif

                        @if(Auth::check())
                            <button type="button"
                                class="action-btn dislike-btn {{ $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'FLOP')->count() ? 'active' : '' }}"
                                title="Dislike"
                                onclick="reactToPost({{ $post->id }}, 'FLOP', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                                    <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                                </svg>
                            </button>
                            <span class="vote-count dislike-count" data-post-dislikes="{{ $post->id }}">{{ $post->reactions->where('appreciation.type', 'FLOP')->count() }}</span>
                        @else
                            <div class="action-btn dislike-btn" style="cursor: default; opacity: 0.5;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                                    <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                                </svg>
                            </div>
                            <span class="vote-count dislike-count">{{ $post->reactions->where('appreciation.type', 'FLOP')->count() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('posts.show', $post->id) }}" class="interaction-btn"
                        style="transition: all 0.2s;"
                        onmouseover="this.style.color='var(--accent-primary)'; this.style.background='rgba(124, 58, 237, 0.1)';"
                        onmouseout="this.style.color=''; this.style.background='';">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg>
                        {{ $post->comments_count }} Comments
                    </a>
                    @if(Auth::check() && Auth::id() !== $post->user_id)
                        <button type="button" 
                                onclick="openGlobalReportModal('post', {{ $post->id }}, '{{ addslashes($post->title) }}')"
                                class="interaction-btn report-btn" title="Report this content" 
                                style="transition: all 0.2s; border: none; background: none; cursor: pointer; display: flex; align-items: center; gap: 4px;" 
                                onmouseover="this.style.color='#f97316'; this.style.background='rgba(249, 115, 22, 0.1)';" 
                                onmouseout="this.style.color=''; this.style.background='';">
                            <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                            Report
                        </button>
                    @endif
                </div>
            </article>
        @empty

            {{-- ================================================
                 EMPTY STATE — ISO 9241 : feedback informatif
            =============================================== --}}
            <div style="background: white; border-radius: var(--radius-lg);
                        border: 1px dashed var(--border-glass); padding: 64px 20px; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(124, 58, 237, 0.06);
                            display: flex; align-items: center; justify-content: center;
                            margin: 0 auto 20px auto; color: var(--accent-primary);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 20v-6M6 20V10M18 20V4"></path>
                    </svg>
                </div>
                <h3 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin: 0 0 8px 0;">
                    No posts yet
                </h3>
                <p style="font-size: 14px; color: var(--text-secondary); margin: 0; max-width: 320px; margin-inline: auto; line-height: 1.6;">
                    u/{{ $user->username }} hasn't published anything yet. Check back later!
                </p>
            </div>

        @endforelse

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div style="margin-top: 28px;">
                {{ $posts->links() }}
            </div>
        @endif

    </div>

@endsection
